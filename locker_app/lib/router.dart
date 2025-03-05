import 'package:go_router/go_router.dart';
import 'package:locker_app/screens/home.dart';
import 'package:locker_app/screens/item_details.dart';
import 'package:locker_app/screens/items.dart';
import 'package:locker_app/screens/login.dart';
import 'package:locker_app/screens/profile.dart';
import 'package:locker_app/services/user_service.dart';
import 'package:provider/provider.dart';

GoRouter router() {
  return GoRouter(
    initialLocation: LoginScreen.route,
    routes: [
      GoRoute(
        path: LoginScreen.route,
        builder: (context, state) => const LoginScreen(),
      ),
      GoRoute(
        path: HomeScreen.route,
        builder: (context, state) => const HomeScreen(),
      ),
      GoRoute(
        path: ItemsScreen.route,
        builder: (context, state) => const ItemsScreen(),
      ),
      GoRoute(
          path: ItemDetailsScreen.route,
          builder: (context, state) {
            final id = state.pathParameters['id'];
            return ItemDetailsScreen(itemId: id ?? '');
          }),
      GoRoute(
          path: ProfileScreen.route,
          builder: (context, state) => const ProfileScreen()),
    ],
    redirect: (context, state) async {
      final isAuthenticated = context.read<UserService>().isAuthenticated;
      if (!isAuthenticated) return '/login';
      return state.matchedLocation;
    },
  );
}
