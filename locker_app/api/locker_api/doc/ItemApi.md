# locker_api.api.ItemApi

## Load the API package
```dart
import 'package:locker_api/api.dart';
```

All URIs are relative to *http://localhost/api*

Method | HTTP request | Description
------------- | ------------- | -------------
[**itemIndex**](ItemApi.md#itemindex) | **GET** /items | Get all Items


# **itemIndex**
> List<Item> itemIndex()

Get all Items

### Example
```dart
import 'package:locker_api/api.dart';
// TODO Configure HTTP Bearer authorization: http
// Case 1. Use String Token
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken('YOUR_ACCESS_TOKEN');
// Case 2. Use Function which generate token.
// String yourTokenGeneratorFunction() { ... }
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken(yourTokenGeneratorFunction);

final api_instance = ItemApi();

try {
    final result = api_instance.itemIndex();
    print(result);
} catch (e) {
    print('Exception when calling ItemApi->itemIndex: $e\n');
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**List<Item>**](Item.md)

### Authorization

[http](../README.md#http)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

