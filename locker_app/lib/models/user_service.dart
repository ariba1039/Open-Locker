import 'package:locker_api/api.dart';

class UserService {
  String _user = '';
  String _token = '';

  bool get isAuthenticated => _token.isNotEmpty;
  String get user => _user;
  String get token => _token;

  setUser(TokenResponse response) {
    _user = response.name;
    _token = response.token;
  }

  clearUser() {
    _user = '';
    _token = '';
  }
}
