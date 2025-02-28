import 'package:flutter/material.dart';
import 'package:locker_api/api.dart';
import 'package:locker_app/models/item_service.dart';
import 'package:locker_app/widgets/no_items_borrowed.dart';
import 'package:provider/provider.dart';

class BorrowedItems extends StatelessWidget {
  const BorrowedItems({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Consumer(builder: (context, ItemService itemService, child) {
      return Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 840),
          child: FutureBuilder(
            future: itemService.getBorrowedItems(),
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return Center(child: CircularProgressIndicator());
              }
              if (snapshot.hasError) {
                return Center(child: Text('An error occurred'));
              }

              final items = snapshot.data!;

              if (items.isEmpty) {
                return NoItemsBorrowed();
              }

              return ItemList(items: items);
            },
          ),
        ),
      );
    });
  }
}

class ItemList extends StatelessWidget {
  const ItemList({
    super.key,
    required this.items,
  });

  final List<Item> items;

  @override
  Widget build(BuildContext context) {
    return GridView.builder(
      itemCount: items.length,
      itemBuilder: (context, index) {
        final item = items[index];
        return Card(
          child: ListTile(
            title: Text(item.name),
            subtitle: Text(item.description),
          ),
        );
      },
      gridDelegate: SliverGridDelegateWithMaxCrossAxisExtent(
        maxCrossAxisExtent: 400,
        childAspectRatio: 1.5,
      ),
    );
  }
}
