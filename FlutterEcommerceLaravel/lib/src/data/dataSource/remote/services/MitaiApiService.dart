import 'dart:convert';
import 'package:ecommerce_flutter/src/domain/models/MitaiProduct.dart';
import 'package:ecommerce_flutter/src/domain/models/ProductVariant.dart';
import 'package:ecommerce_flutter/src/domain/utils/Resource.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class MitaiApiService {
  static const String _baseHost = 'mitaicr.com';
  static const String _tenant = 'mitaicr';

  Future<String?> _getToken() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final userJson = prefs.getString('user');
      if (userJson == null) return null;
      final decoded = json.decode(userJson);
      return decoded['token'] as String?;
    } catch (_) {
      return null;
    }
  }

  Map<String, String> _headers(String? token) {
    final headers = <String, String>{
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
    if (token != null && token.isNotEmpty) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  // POST https://mitaicr.com/api/login
  Future<Resource<Map<String, dynamic>>> login(
      String email, String password) async {
    try {
      final url = Uri.https(_baseHost, '/api/login');
      final body = json.encode({'email': email, 'password': password});
      final response = await http.post(
        url,
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: body,
      );
      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 200 || response.statusCode == 201) {
        return Success(data);
      } else {
        final msg = data['message']?.toString() ?? 'Login error';
        return Error(msg);
      }
    } catch (e) {
      return Error(e.toString());
    }
  }

  String _parseError(http.Response response, String fallback) {
    try {
      final data = json.decode(response.body) as Map<String, dynamic>;
      return data['message']?.toString() ?? fallback;
    } catch (_) {
      return '$fallback (HTTP ${response.statusCode})';
    }
  }

  // GET https://mitaicr.com/api/home/admin/mitaicr
  Future<Resource<Map<String, dynamic>>> getHomeAdmin() async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/home/admin/$_tenant');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        return Success(data);
      } else {
        return Error(_parseError(response, 'Error al cargar el catálogo'));
      }
    } catch (e) {
      return Error(e.toString());
    }
  }

  // GET https://mitaicr.com/api/categories/by-department/{deptId}/mitaicr
  Future<Resource<List<dynamic>>> getCategoriesByDepartment(int deptId) async {
    try {
      final token = await _getToken();
      final url = Uri.https(
          _baseHost, '/api/categories/by-department/$deptId/$_tenant');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        final list = data['data'] as List<dynamic>? ?? [];
        return Success(list);
      } else {
        return Error(_parseError(response, 'Error al cargar categorías'));
      }
    } catch (e) {
      return Error(e.toString());
    }
  }

  // GET https://mitaicr.com/api/products/category/{categoryId}/mitaicr?status=1
  Future<Resource<List<MitaiProduct>>> getProductsByCategory(
      int categoryId) async {
    try {
      final token = await _getToken();
      final url = Uri.https(
        _baseHost,
        '/api/products/category/$categoryId/$_tenant',
        {'status': '1'},
      );
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        final list = data['data'] as List<dynamic>? ?? [];
        return Success(MitaiProduct.fromJsonList(list));
      } else {
        return Error(_parseError(response, 'Error al cargar productos'));
      }
    } catch (e) {
      return Error(e.toString());
    }
  }

  // GET https://mitaicr.com/api/products/{productId}/variants/mitaicr
  Future<Resource<List<ProductVariant>>> getProductVariants(
      int productId) async {
    try {
      final token = await _getToken();
      final url =
          Uri.https(_baseHost, '/api/products/$productId/variants/$_tenant');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        final list = data['data'] as List<dynamic>? ?? [];
        return Success(ProductVariant.fromJsonList(list));
      } else {
        return Error(_parseError(response, 'Error al cargar variantes'));
      }
    } catch (e) {
      return Error(e.toString());
    }
  }
}
