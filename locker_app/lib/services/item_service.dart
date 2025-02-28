import 'package:locker_api/api.dart';
import 'package:locker_app/services/user_service.dart';

class ItemService {
  ItemService({required UserService userService}) : _userService = userService;

  final UserService _userService;

  Future<List<Item>> getBorrowedItems() async {
    final client = ApiClient(
        authentication: HttpBearerAuth()..accessToken = _userService.token);
    return await ItemApi(client).itemsBorrowed() ?? [];
  }

  Future<List<Item>> getItems() async {
    final client = ApiClient(
        authentication: HttpBearerAuth()..accessToken = _userService.token);
    return await ItemApi(client).itemsIndex() ?? [];
  }
}
