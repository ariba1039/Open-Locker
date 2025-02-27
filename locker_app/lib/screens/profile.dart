import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/models/auth_state.dart';
import 'package:locker_app/widgets/bottom_nav.dart';
import 'package:locker_app/widgets/side_nav.dart';
import 'package:provider/provider.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final useSideNavRail = MediaQuery.sizeOf(context).width >= 600;
    final name = context.watch<AuthState>().userName;
    return Scaffold(
        appBar: AppBar(
          title: Text('Profile'),
          backgroundColor: Theme.of(context).primaryColor,
          actions: [
            IconButton(
              icon: const Icon(Icons.logout),
              onPressed: () {
                context.read<AuthState>().logout();
                context.go('/login');
              },
            ),
          ],
        ),
        body: Row(
          children: [
            if (useSideNavRail)
              const SideNav(
                selectedIndex: 1,
              ),
            Expanded(
              child: Center(child: Text(name)),
            ),
          ],
        ),
        bottomNavigationBar: useSideNavRail ? null : const BottomNav());
  }
}
