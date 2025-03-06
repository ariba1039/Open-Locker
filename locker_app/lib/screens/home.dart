import 'package:flutter/material.dart';
import 'package:locker_app/widgets/borrowed_items.dart';
import 'package:locker_app/widgets/side_nav.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});
  static const route = '/home';

  @override
  Widget build(BuildContext context) {
    final useSideNavRail = MediaQuery.sizeOf(context).width >= 600;
    return Scaffold(
      appBar: AppBar(
        title: Text('My Locker'),
        backgroundColor: Theme.of(context).primaryColor,
      ),
      body: Row(
        children: [
          if (useSideNavRail)
            const SideNav(
              selectedIndex: 0,
            ),
          const Expanded(
            child: BorrowedItems(),
          ),
        ],
      ),
    );
  }
}
