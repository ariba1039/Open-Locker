import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/screens/login.dart';
import 'package:locker_app/services/user_service.dart';
import 'package:provider/provider.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});
  static const route = '/profile';

  @override
  Widget build(BuildContext context) {
    final name = context.watch<UserService>().user;
    return Scaffold(
      body: Center(
        child: Text(name),
      ),
      bottomNavigationBar: Padding(
        padding: const EdgeInsets.only(bottom: 16.0),
        child: TextButton.icon(
          onPressed: () async {
            await context.read<UserService>().logout();
            if (context.mounted) {
              context.go(LoginScreen.route);
            }
          },
          icon: const Icon(Icons.logout),
          label: const Text('Logout'),
        ),
      ),
    );
  }
}
