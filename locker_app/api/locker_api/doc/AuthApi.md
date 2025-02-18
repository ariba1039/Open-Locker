# locker_api.api.AuthApi

## Load the API package
```dart
import 'package:locker_api/api.dart';
```

All URIs are relative to *http://localhost/api*

Method | HTTP request | Description
------------- | ------------- | -------------
[**authLogin**](AuthApi.md#authlogin) | **POST** /login | Login
[**authLogout**](AuthApi.md#authlogout) | **POST** /logout | Logout
[**authRegister**](AuthApi.md#authregister) | **POST** /register | Register
[**authUser**](AuthApi.md#authuser) | **GET** /user | Get current User


# **authLogin**
> TokenResponse authLogin(authLoginRequest)

Login

### Example
```dart
import 'package:locker_api/api.dart';
// TODO Configure HTTP Bearer authorization: http
// Case 1. Use String Token
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken('YOUR_ACCESS_TOKEN');
// Case 2. Use Function which generate token.
// String yourTokenGeneratorFunction() { ... }
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken(yourTokenGeneratorFunction);

final api_instance = AuthApi();
final authLoginRequest = AuthLoginRequest(); // AuthLoginRequest | 

try {
    final result = api_instance.authLogin(authLoginRequest);
    print(result);
} catch (e) {
    print('Exception when calling AuthApi->authLogin: $e\n');
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **authLoginRequest** | [**AuthLoginRequest**](AuthLoginRequest.md)|  | 

### Return type

[**TokenResponse**](TokenResponse.md)

### Authorization

[http](../README.md#http)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **authLogout**
> AuthLogout200Response authLogout()

Logout

### Example
```dart
import 'package:locker_api/api.dart';
// TODO Configure HTTP Bearer authorization: http
// Case 1. Use String Token
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken('YOUR_ACCESS_TOKEN');
// Case 2. Use Function which generate token.
// String yourTokenGeneratorFunction() { ... }
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken(yourTokenGeneratorFunction);

final api_instance = AuthApi();

try {
    final result = api_instance.authLogout();
    print(result);
} catch (e) {
    print('Exception when calling AuthApi->authLogout: $e\n');
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**AuthLogout200Response**](AuthLogout200Response.md)

### Authorization

[http](../README.md#http)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **authRegister**
> TokenResponse authRegister(authRegisterRequest)

Register

### Example
```dart
import 'package:locker_api/api.dart';
// TODO Configure HTTP Bearer authorization: http
// Case 1. Use String Token
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken('YOUR_ACCESS_TOKEN');
// Case 2. Use Function which generate token.
// String yourTokenGeneratorFunction() { ... }
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken(yourTokenGeneratorFunction);

final api_instance = AuthApi();
final authRegisterRequest = AuthRegisterRequest(); // AuthRegisterRequest | 

try {
    final result = api_instance.authRegister(authRegisterRequest);
    print(result);
} catch (e) {
    print('Exception when calling AuthApi->authRegister: $e\n');
}
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **authRegisterRequest** | [**AuthRegisterRequest**](AuthRegisterRequest.md)|  | 

### Return type

[**TokenResponse**](TokenResponse.md)

### Authorization

[http](../README.md#http)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

# **authUser**
> User authUser()

Get current User

### Example
```dart
import 'package:locker_api/api.dart';
// TODO Configure HTTP Bearer authorization: http
// Case 1. Use String Token
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken('YOUR_ACCESS_TOKEN');
// Case 2. Use Function which generate token.
// String yourTokenGeneratorFunction() { ... }
//defaultApiClient.getAuthentication<HttpBearerAuth>('http').setAccessToken(yourTokenGeneratorFunction);

final api_instance = AuthApi();

try {
    final result = api_instance.authUser();
    print(result);
} catch (e) {
    print('Exception when calling AuthApi->authUser: $e\n');
}
```

### Parameters
This endpoint does not need any parameter.

### Return type

[**User**](User.md)

### Authorization

[http](../README.md#http)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../README.md#documentation-for-api-endpoints) [[Back to Model list]](../README.md#documentation-for-models) [[Back to README]](../README.md)

