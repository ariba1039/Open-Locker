import 'package:flutter/material.dart';
import 'package:locker_api/api.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AuthState with ChangeNotifier {
  String _token = '';
  String _userName = '';
  ApiClient _client = ApiClient();

  bool get isAuthenticated => _token.isNotEmpty;
  String get userName => _userName;
  ApiClient get client => _client;

  Future<void> login(String email, String password) async {
    final response = await AuthApi(_client)
        .authLogin(AuthLoginRequest(email: email, password: password));
    if (response != null) {
      _token = response.token;
      await _persistToken();
      _client = ApiClient(
        authentication: HttpBearerAuth()..accessToken = _token,
      );
      _userName = response.name;
      notifyListeners();
    }
  }

  Future<void> _persistToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', _token);
  }

  Future<void> _loadToken() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('token') ?? '';
    if (_token.isNotEmpty) {
      _client = ApiClient(
        authentication: HttpBearerAuth()..accessToken = _token,
      );
    }
  }

  Future<void> loadUser() async {
    if (_token.isEmpty) await _loadToken();
    if (_token.isNotEmpty) {
      final response = await AuthApi(_client).authUser();
      if (response != null) {
        _userName = response.name;
        notifyListeners();
      }
    }
  }

  void logout() {
    _token = '';
    _userName = '';
    notifyListeners();
  }
}
