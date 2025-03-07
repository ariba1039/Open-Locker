import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/screens/home.dart';
import 'package:locker_app/screens/item_details.dart';
import 'package:locker_app/screens/items.dart';
import 'package:locker_app/screens/login.dart';
import 'package:locker_app/screens/profile.dart';
import 'package:locker_app/screens/splash.dart';
import 'package:locker_app/services/user_service.dart';
import 'package:locker_app/widgets/bottom_nav.dart';
import 'package:locker_app/widgets/side_nav.dart';
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
    final useSideNavRail = MediaQuery.sizeOf(context).width >= 600;
    final selectedIndex = _calculateSelectedIndex(location);
    return Scaffold(
      appBar: AppBar(
        title: Text(location),
        backgroundColor: Theme.of(context).primaryColor,
      ),
      body: useSideNavRail
          ? Row(
              children: [
                if (useSideNavRail)
                  SideNav(
                    selectedIndex: selectedIndex,
                  ),
                Expanded(
                  child: child,
                ),
              ],
            )
          : child,
      bottomNavigationBar: useSideNavRail
          ? null
          : BottomNav(
              selectedIndex: selectedIndex,
            ),
    );
  }
}

GoRouter router() {
  return GoRouter(
    navigatorKey: _rootNavigatorKey,
    initialLocation: SplashScreen.route,
    routes: [
      GoRoute(
        path: SplashScreen.route,
        builder: (context, state) => const SplashScreen(),
      ),
      GoRoute(
        path: LoginScreen.route,
        pageBuilder: (context, state) => CustomTransitionPage(
          key: state.pageKey,
          child: const LoginScreen(),
          transitionsBuilder: (context, animation, secondaryAnimation, child) {
            return FadeTransition(
              opacity: animation,
              child: child,
            );
          },
          transitionDuration: const Duration(milliseconds: 300),
        ),
      ),
      ShellRoute(
        navigatorKey: _shellNavigatorKey,
        pageBuilder: (context, state, child) => CustomTransitionPage(
          key: state.pageKey,
          child: ScaffoldWithNavBar(
            location: state.uri.path,
            child: child,
          ),
          transitionsBuilder: (context, animation, secondaryAnimation, child) {
            return FadeTransition(
              opacity: animation,
              child: child,
            );
          },
          transitionDuration: const Duration(milliseconds: 300),
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
                  child: ItemDetailsScreen(
                      itemId: state.pathParameters['id'] ?? ''),
                  transitionsBuilder:
                      (context, animation, secondaryAnimation, child) {
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
      final userService = context.read<UserService>();

      // Wenn wir auf dem Splash-Screen sind, keine Weiterleitung
      if (state.matchedLocation == SplashScreen.route) {
        return null;
      }

      // Wenn wir bereits auf der Login-Route sind und nicht authentifiziert sind,
      // bleiben wir dort
      if (state.matchedLocation == LoginScreen.route &&
          !userService.isAuthenticated) {
        return null;
      }

      // Wenn wir nicht authentifiziert sind und nicht auf der Login-Route sind,
      // gehen wir zum Login
      if (!userService.isAuthenticated) {
        return LoginScreen.route;
      }

      // Wenn wir authentifiziert sind und auf der Login-Route sind,
      // gehen wir zur Home-Route
      if (userService.isAuthenticated &&
          state.matchedLocation == LoginScreen.route) {
        return HomeScreen.route;
      }

      // In allen anderen Fällen bleiben wir wo wir sind
      return null;
    },
  );
}
