import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class SideNav extends StatelessWidget {
  const SideNav({super.key});

  @override
  Widget build(BuildContext context) {
    return NavigationRail(
      destinations: const [
        NavigationRailDestination(
          icon: Icon(Icons.home),
          label: Text('Home'),
        ),
        NavigationRailDestination(
          icon: Icon(Icons.account_circle),
          label: Text('Profile'),
        ),
      ],
      onDestinationSelected: (int index) {
        if (index == 0) {
          context.go('/home');
        } else if (index == 1) {
          context.go('/profile');
        }
      },
      selectedIndex: 0,
      labelType: NavigationRailLabelType.all,
    );
  }
}
