import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

class BottomNav extends StatelessWidget {
  final int selectedIndex;
  const BottomNav({super.key, required this.selectedIndex});

  @override
  Widget build(BuildContext context) {
    return BottomNavigationBar(
      items: const [
        BottomNavigationBarItem(
          icon: Icon(Icons.home),
          label: 'Home',
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.account_circle),
          label: 'Profile',
        ),
      ],
      onTap: (index) {
        if (index == 0) {
          context.go('/home');
        } else if (index == 1) {
          context.go('/profile');
        }
      },
      currentIndex: selectedIndex,
    );
  }
}
