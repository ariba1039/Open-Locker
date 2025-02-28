import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_api/api.dart';
import 'package:locker_app/models/user_service.dart';
import 'package:locker_app/screens/home.dart';
import 'package:locker_app/widgets/login_form.dart';
import 'package:provider/provider.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});
  static const route = '/login';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            LoginForm(
              onSubmit: (TokenResponse response) =>
                  _onFormSubmitted(context, response),
            ),
          ],
        ),
      ),
    );
  }

  _onFormSubmitted(BuildContext context, TokenResponse response) {
    Provider.of<UserService>(context, listen: false).setUser(response);
    context.pushReplacement(HomeScreen.route);
  }
}
