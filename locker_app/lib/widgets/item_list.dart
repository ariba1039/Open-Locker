import 'package:flutter/material.dart';
import 'package:locker_api/api.dart';
import 'package:locker_app/models/auth_state.dart';
import 'package:provider/provider.dart';

class ItemList extends StatelessWidget {
  const ItemList({
    super.key,
  });

  @override
  Widget build(BuildContext context) {
    return Consumer(builder: (context, AuthState authState, child) {
      return Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 840),
          child: FutureBuilder(
            future: ItemApi(authState.client).itemsIndex(),
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return Center(child: CircularProgressIndicator());
              }
              if (snapshot.hasError) {
                return Center(child: Text('An error occurred'));
              }
              final items = snapshot.data ?? [];
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
            },
          ),
        ),
      );
    });
  }
}
