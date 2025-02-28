import 'package:flutter/material.dart';
import 'package:locker_api/api.dart';

class LoginForm extends StatefulWidget {
  final void Function(TokenResponse tokenResponse) onSubmit;

  const LoginForm({
    super.key,
    required this.onSubmit,
  });

  @override
  State<LoginForm> createState() => _LoginFormState();
}

class _LoginFormState extends State<LoginForm> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Column(
        children: [
          TextFormField(
            controller: _emailController,
            decoration: const InputDecoration(
              labelText: 'Email',
            ),
            keyboardType: TextInputType.emailAddress,
            validator: (value) {
              if (value == null || value.isEmpty) {
                return 'Please enter your email';
              }
              return null;
            },
          ),
          TextFormField(
            controller: _passwordController,
            decoration: const InputDecoration(
              labelText: 'Password',
            ),
            obscureText: true,
            validator: (value) {
              if (value == null || value.isEmpty) {
                return 'Please enter your password';
              }
              return null;
            },
          ),
          ElevatedButton(
            onPressed: () async {
              if (_formKey.currentState!.validate()) {
                final email = _emailController.text;
                final password = _passwordController.text;
                var authLoginRequest =
                    AuthLoginRequest(email: email, password: password);
                final tokenResponse =
                    await AuthApi().authLogin(authLoginRequest);

                if (tokenResponse != null) {
                  widget.onSubmit(tokenResponse);
                }
              }
            },
            child: const Text('Login'),
          ),
        ],
      ),
    );
  }
}
