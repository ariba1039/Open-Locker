import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:locker_app/screens/home.dart';
import 'package:locker_app/services/user_service.dart';
import 'package:locker_app/widgets/login_form.dart';
import 'package:provider/provider.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});
  static const route = '/login';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 840),
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: <Widget>[
                LoginForm(
                  onSubmit: (String email, String password) =>
                      _onFormSubmitted(context, email, password),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  _onFormSubmitted(BuildContext context, String email, String password) async {
    await Provider.of<UserService>(context, listen: false)
        .login(email, password);
    context.pushReplacement(HomeScreen.route);
  }
}
