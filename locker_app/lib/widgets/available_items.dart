import 'package:flutter/material.dart';
import 'package:locker_app/services/item_service.dart';
import 'package:locker_app/widgets/item_list.dart';
import 'package:locker_app/widgets/no_items_available.dart';
import 'package:provider/provider.dart';

class AvailableItems extends StatelessWidget {
  const AvailableItems({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Consumer(builder: (context, ItemService itemService, child) {
      return Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 840),
          child: FutureBuilder(
            future: itemService.getItems(),
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return Center(child: CircularProgressIndicator());
              }
              if (snapshot.hasError) {
                return Center(child: Text('An error occurred'));
              }

              final items = snapshot.data!;

              if (items.isEmpty) {
                return NoItemsAvailable();
              }

              return ItemList(items: items);
            },
          ),
        ),
      );
    });
  }
}
