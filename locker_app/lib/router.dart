import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/screens/home.dart';
import 'package:locker_app/screens/item_details.dart';
import 'package:locker_app/screens/items.dart';
import 'package:locker_app/screens/login.dart';
import 'package:locker_app/screens/profile.dart';
import 'package:locker_app/services/user_service.dart';
import 'package:locker_app/widgets/bottom_nav.dart';
import 'package:provider/provider.dart';

final _rootNavigatorKey = GlobalKey<NavigatorState>();
final _shellNavigatorKey = GlobalKey<NavigatorState>();

class ScaffoldWithNavBar extends StatelessWidget {
  const ScaffoldWithNavBar({
    super.key,
    required this.child,
    required this.location,
  });

  final Widget child;
  final String location;

  int _calculateSelectedIndex(String location) {
    if (location.startsWith('/home')) return 0;
    if (location.startsWith('/items')) return 1;
    if (location.startsWith('/profile')) return 2;
    return 0;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: child,
      bottomNavigationBar: BottomNav(
        selectedIndex: _calculateSelectedIndex(location),
      ),
    );
  }
}

GoRouter router() {
  return GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: LoginScreen.route,
    routes: [
      GoRoute(
        path: LoginScreen.route,
        builder: (context, state) => const LoginScreen(),
      ),
      ShellRoute(
        navigatorKey: _shellNavigatorKey,
        builder: (context, state, child) => ScaffoldWithNavBar(
          child: child,
          location: state.uri.path,
        ),
        routes: [
          GoRoute(
            path: HomeScreen.route,
            builder: (context, state) => const HomeScreen(),
          ),
          GoRoute(
            path: ItemsScreen.route,
            builder: (context, state) => const ItemsScreen(),
            routes: [
              GoRoute(
                path: ':id',
                parentNavigatorKey: _rootNavigatorKey,
                pageBuilder: (context, state) => CustomTransitionPage(
                  key: state.pageKey,
                  child: ItemDetailsScreen(itemId: state.pathParameters['id'] ?? ''),
                  transitionsBuilder: (context, animation, secondaryAnimation, child) {
                    return SlideTransition(
                      position: animation.drive(
                        Tween(
                          begin: const Offset(1.0, 0.0),
                          end: Offset.zero,
                        ).chain(CurveTween(curve: Curves.easeInOut)),
                      ),
                      child: child,
                    );
                  },
                  transitionDuration: const Duration(milliseconds: 300),
                ),
              ),
            ],
          ),
          GoRoute(
            path: ProfileScreen.route,
            builder: (context, state) => const ProfileScreen(),
          ),
        ],
      ),
    ],
    redirect: (context, state) async {
      final isAuthenticated = context.read<UserService>().isAuthenticated;
      if (!isAuthenticated) return '/login';
      return state.matchedLocation;
    },
  );
}
