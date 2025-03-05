import 'package:flutter/material.dart';
import 'package:locker_app/widgets/bottom_nav.dart';
import 'package:locker_app/widgets/item_details.dart';
import 'package:locker_app/widgets/side_nav.dart';

class ItemDetailsScreen extends StatelessWidget {
  final String itemId;
  const ItemDetailsScreen({super.key, required this.itemId});
  static const route = '/items/:id';

  @override
  Widget build(BuildContext context) {
    final useSideNavRail = MediaQuery.sizeOf(context).width >= 600;
    return Scaffold(
        appBar: AppBar(
          title: Text('Item Details'),
          backgroundColor: Theme.of(context).primaryColor,
        ),
        body: Row(
          children: [
            if (useSideNavRail)
              const SideNav(
                selectedIndex: 1,
              ),
            Expanded(
                child: Padding(
              padding: const EdgeInsets.all(8.0),
              child: ItemDetails(itemId: itemId),
            )),
          ],
        ),
        bottomNavigationBar: useSideNavRail
            ? null
            : const BottomNav(
                selectedIndex: 1,
              ));
  }
}
