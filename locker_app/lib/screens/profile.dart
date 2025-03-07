import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/screens/login.dart';
import 'package:locker_app/services/user_service.dart';
import 'package:locker_app/widgets/side_nav.dart';
import 'package:provider/provider.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});
  static const route = '/profile';

  @override
  Widget build(BuildContext context) {
    final name = context.watch<UserService>().user;
    return Center(child: Text(name));
  }
}
