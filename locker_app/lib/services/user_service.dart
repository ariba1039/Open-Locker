import 'package:locker_api/api.dart';
import 'package:shared_preferences/shared_preferences.dart';

class UserWithToken {
  final User user;
  final String token;

  UserWithToken({required this.user, required this.token});
}

class UserService {
  User? _user;
  String? _token;

  bool get isAuthenticated => token.isNotEmpty;
  String get user => _user?.name ?? '';
  String get token => _token ?? '';

  UserService({UserWithToken? userWithToken}) {
    if (userWithToken != null) {
      _user = userWithToken.user;
      _token = userWithToken.token;
    }
  }

  Future<void> logout() async {
    _user = null;
    _token = '';
  }

  Future<UserWithToken?> setToken(String token) async {
    _token = token;
    await _persistToken();
    await _loadUser();

    if (_user == null) {
      return null;
    }

    return UserWithToken(user: _user!, token: token);
  }

  Future<void> _loadUser() async {
    final client =
        ApiClient(authentication: HttpBearerAuth()..accessToken = token);
    _user = await AuthApi(client).authUser();
  }

  _persistToken() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.setString('token', token);
  }

  _clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    prefs.remove('token');
  }

  Future<UserWithToken> login(String email, String password) async {
    var authLoginRequest = AuthLoginRequest(email: email, password: password);
    final tokenResponse = await AuthApi().authLogin(authLoginRequest);

    if (tokenResponse == null || tokenResponse.token.isEmpty) {
      throw Exception('No token in response');
    }

    final userWithToken = await setToken(tokenResponse.token);

    if (userWithToken == null) {
      throw Exception('No user in response');
    }

    return userWithToken;
  }
}
