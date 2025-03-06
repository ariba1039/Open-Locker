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
  bool _initialized = false;

  bool get initialized => _initialized;
  bool get isAuthenticated => _initialized && token.isNotEmpty;
  String get user => _user?.name ?? '';
  String get token => _token ?? '';

  UserService() {
    _loadPersistedToken();
  }

  Future<void> _loadPersistedToken() async {
    final prefs = await SharedPreferences.getInstance();
    final savedToken = prefs.getString('token') ?? '';
    if (savedToken.isNotEmpty) {
      await setToken(savedToken);
    }
    _initialized = true;
  }

  Future<void> logout() async {
    _user = null;
    _token = '';
    await _clearToken();
  }

  Future<UserWithToken?> setToken(String token) async {
    _token = token;
    await _persistToken();
    await _loadUser();

    if (_user == null) {
      _token = '';
      await _clearToken();
      return null;
    }

    return UserWithToken(user: _user!, token: token);
  }

  Future<void> _loadUser() async {
    try {
      final client =
          ApiClient(authentication: HttpBearerAuth()..accessToken = token);
      _user = await AuthApi(client).authUser();
    } catch (e) {
      await logout();
    }
  }

  Future<void> _persistToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('token', token);
  }

  Future<void> _clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('token');
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

  Future<void> waitForInitialization() async {
    if (!_initialized) {
      await _loadPersistedToken();
    }
  }
}
