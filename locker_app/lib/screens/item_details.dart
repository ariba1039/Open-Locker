import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/widgets/item_details.dart';

class ItemDetailsScreen extends StatelessWidget {
  final String itemId;
  const ItemDetailsScreen({super.key, required this.itemId});
  static const route = '/items/:id';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back),
          onPressed: () => context.pop(),
        ),
        title: const Text('Item Details'),
        backgroundColor: Theme.of(context).primaryColor,
      ),
      body: Padding(
        padding: const EdgeInsets.all(8.0),
        child: ItemDetails(itemId: itemId),
      ),
    );
  }
}
