import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/common/theme.dart';
import 'package:locker_app/models/item_service.dart';
import 'package:locker_app/models/user_service.dart';
import 'package:locker_app/screens/home.dart';
import 'package:locker_app/screens/login.dart';
import 'package:locker_app/screens/profile.dart';
import 'package:provider/provider.dart';

void main() {
  runApp(const MainApp());
}

GoRouter router() {
  return GoRouter(
    initialLocation: '/login',
    routes: [
      GoRoute(
        path: '/login',
        builder: (context, state) => const LoginScreen(),
      ),
      GoRoute(
        path: '/home',
        builder: (context, state) => const HomeScreen(),
      ),
      GoRoute(
          path: '/profile', builder: (context, state) => const ProfileScreen()),
    ],
    redirect: (context, state) async {
      final isAuthenticated = context.read<UserService>().isAuthenticated;
      if (!isAuthenticated) return '/login';
      return state.matchedLocation;
    },
  );
}

class MainApp extends StatelessWidget {
  const MainApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        Provider(create: (_) => UserService()),
        ProxyProvider<UserService, ItemService>(
          update: (_, userService, __) => ItemService(userService: userService),
        ),
      ],
      child: MaterialApp.router(
        title: 'Provider Demo',
        theme: appTheme,
        routerConfig: router(),
      ),
    );
  }
}
