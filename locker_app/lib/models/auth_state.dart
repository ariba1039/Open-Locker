import 'package:flutter/material.dart';
import 'package:locker_api/api.dart';

class AuthState with ChangeNotifier {
  bool _isAuthenticated = false;
  String _userName = '';
  ApiClient _client = ApiClient();

  bool get isAuthenticated => _isAuthenticated;
  String get userName => _userName;
  ApiClient get client => _client;

  Future<void> login(String email, String password) async {
    final response = await AuthApi(_client)
        .authLogin(AuthLoginRequest(email: email, password: password));
    if (response != null) {
      _client = ApiClient(
        authentication: HttpBearerAuth()..accessToken = response.token,
      );
      _isAuthenticated = true;
      _userName = response.name;
      notifyListeners();
    }
  }

  void logout() {
    _isAuthenticated = false;
    _userName = '';
    notifyListeners();
  }
}
