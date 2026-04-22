import 'dart:convert';
import 'dart:io';
import 'package:ecommerce_flutter/src/domain/models/AttributeType.dart';
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
    final h = <String, String>{'Content-Type': 'application/json', 'Accept': 'application/json'};
    if (token != null && token.isNotEmpty) h['Authorization'] = 'Bearer $token';
    return h;
  }

  Map<String, String> _authHeaders(String? token) {
    final h = <String, String>{'Accept': 'application/json'};
    if (token != null && token.isNotEmpty) h['Authorization'] = 'Bearer $token';
    return h;
  }

  String _parseError(http.Response response, String fallback) {
    try {
      final data = json.decode(response.body) as Map<String, dynamic>;
      return data['message']?.toString() ?? fallback;
    } catch (_) {
      return '$fallback (HTTP ${response.statusCode})';
    }
  }

  // ─── Auth ─────────────────────────────────────────────────────────────────

  Future<Resource<Map<String, dynamic>>> login(String email, String password) async {
    try {
      final url = Uri.https(_baseHost, '/api/login');
      final response = await http.post(url,
          headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
          body: json.encode({'email': email, 'password': password}));
      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 200 || response.statusCode == 201) return Success(data);
      return Error(data['message']?.toString() ?? 'Login error');
    } catch (e) {
      return Error(e.toString());
    }
  }

  // ─── Catalog (read) ───────────────────────────────────────────────────────

  Future<Resource<Map<String, dynamic>>> getHomeAdmin() async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/home/admin/$_tenant');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        return Success(json.decode(response.body) as Map<String, dynamic>);
      }
      return Error(_parseError(response, 'Error al cargar el catálogo'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<List<dynamic>>> getCategoriesByDepartment(int deptId) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/categories/by-department/$deptId/$_tenant');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        return Success(data['data'] as List<dynamic>? ?? []);
      }
      return Error(_parseError(response, 'Error al cargar categorías'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<Map<String, dynamic>>> getProductsByCategoryPaged(
    int categoryId, {
    int page = 1,
    int perPage = 15,
    String status = '1',
    String search = '',
  }) async {
    try {
      final token = await _getToken();
      final params = <String, String>{
        'status': status,
        'page': '$page',
        'per_page': '$perPage',
      };
      if (search.isNotEmpty) params['search'] = search;
      final url = Uri.https(_baseHost, '/api/products/category/$categoryId/$_tenant', params);
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        return Success(json.decode(response.body) as Map<String, dynamic>);
      }
      return Error(_parseError(response, 'Error al cargar productos'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<List<MitaiProduct>>> getProductsByCategory(int categoryId) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/products/category/$categoryId/$_tenant',
          {'status': '1', 'per_page': '200'});
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        return Success(MitaiProduct.fromJsonList(data['data'] as List<dynamic>? ?? []));
      }
      return Error(_parseError(response, 'Error al cargar productos'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<List<ProductVariant>>> getProductVariants(int productId) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/products/$productId/variants/$_tenant');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        return Success(ProductVariant.fromJsonList(data['data'] as List<dynamic>? ?? []));
      }
      return Error(_parseError(response, 'Error al cargar variantes'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  // ─── Admin CRUD ───────────────────────────────────────────────────────────

  Future<Resource<Map<String, dynamic>>> getProductForEdit(int id) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/product/$id');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        return Success(json.decode(response.body) as Map<String, dynamic>);
      }
      return Error(_parseError(response, 'Error al cargar producto'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<Map<String, dynamic>>> createProduct({
    required String name,
    required String code,
    required String description,
    required double price,
    required int stock,
    required bool manageStock,
    required bool trending,
    required double? discount,
    required String? metaKeywords,
    required List<int> categoryIds,
    required List<Map<String, dynamic>> combos,
    required List<File> images,
  }) async {
    try {
      final token = await _getToken();
      final request = http.MultipartRequest('POST', Uri.https(_baseHost, '/api/admin/products'));
      if (token != null) request.headers['Authorization'] = 'Bearer $token';
      request.headers['Accept'] = 'application/json';

      request.fields['name']         = name;
      request.fields['code']         = code;
      request.fields['description']  = description;
      request.fields['price']        = price.toString();
      request.fields['stock']        = stock.toString();
      request.fields['manage_stock'] = manageStock ? '1' : '0';
      request.fields['trending']     = trending ? '1' : '0';
      if (discount != null && discount > 0) request.fields['discount'] = discount.toString();
      if (metaKeywords != null) request.fields['meta_keywords'] = metaKeywords;
      request.fields['category_ids'] = json.encode(categoryIds);
      request.fields['combos']       = json.encode(combos);

      for (final file in images) {
        request.files.add(await http.MultipartFile.fromPath('images[]', file.path));
      }

      final streamed = await request.send();
      final response = await http.Response.fromStream(streamed);
      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 200 || response.statusCode == 201) return Success(data);
      return Error(data['message']?.toString() ?? 'Error al crear producto');
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<Map<String, dynamic>>> updateProduct({
    required int id,
    required String name,
    required String code,
    required String description,
    required double price,
    required int stock,
    required bool manageStock,
    required bool trending,
    required double? discount,
    required String? metaKeywords,
    required List<int> categoryIds,
    required List<Map<String, dynamic>> combos,
    required List<File> newImages,
  }) async {
    try {
      final token = await _getToken();
      final request = http.MultipartRequest('POST', Uri.https(_baseHost, '/api/admin/products/$id'));
      if (token != null) request.headers['Authorization'] = 'Bearer $token';
      request.headers['Accept'] = 'application/json';

      request.fields['name']         = name;
      request.fields['code']         = code;
      request.fields['description']  = description;
      request.fields['price']        = price.toString();
      request.fields['stock']        = stock.toString();
      request.fields['manage_stock'] = manageStock ? '1' : '0';
      request.fields['trending']     = trending ? '1' : '0';
      if (discount != null && discount > 0) request.fields['discount'] = discount.toString();
      if (metaKeywords != null) request.fields['meta_keywords'] = metaKeywords;
      request.fields['category_ids'] = json.encode(categoryIds);
      request.fields['combos']       = json.encode(combos);

      for (final file in newImages) {
        request.files.add(await http.MultipartFile.fromPath('images[]', file.path));
      }

      final streamed = await request.send();
      final response = await http.Response.fromStream(streamed);
      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 200 || response.statusCode == 201) return Success(data);
      return Error(data['message']?.toString() ?? 'Error al actualizar producto');
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<bool>> deleteProduct(int id) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/products/$id');
      final response = await http.delete(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) return const Success(true);
      return Error(_parseError(response, 'Error al eliminar producto'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  // ─── Categories reference ─────────────────────────────────────────────────

  Future<Resource<List<dynamic>>> getAllCategories() async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/categories-all');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        return Success(data['data'] as List<dynamic>? ?? []);
      }
      return Error(_parseError(response, 'Error al cargar categorías'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  // ─── Attributes ───────────────────────────────────────────────────────────

  Future<Resource<List<AttributeType>>> getAllAttributes() async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/attributes-all');
      final response = await http.get(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) {
        final data = json.decode(response.body) as Map<String, dynamic>;
        return Success(AttributeType.fromJsonList(data['data'] as List<dynamic>? ?? []));
      }
      return Error(_parseError(response, 'Error al cargar atributos'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<AttributeType>> createAttribute(String name) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/attributes');
      final response = await http.post(url,
          headers: _headers(token), body: json.encode({'name': name}));
      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 200 || response.statusCode == 201) {
        return Success(AttributeType.fromJson(data['data']));
      }
      return Error(data['message']?.toString() ?? 'Error');
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<AttributeValue>> createAttributeValue(int attrId, String value) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/attributes/$attrId/values');
      final response = await http.post(url,
          headers: _headers(token), body: json.encode({'value': value}));
      final data = json.decode(response.body) as Map<String, dynamic>;
      if (response.statusCode == 200 || response.statusCode == 201) {
        return Success(AttributeValue.fromJson(data['data']));
      }
      return Error(data['message']?.toString() ?? 'Error');
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<bool>> deleteAttribute(int id) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/attributes/$id');
      final response = await http.delete(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) return Success(true);
      return Error(_parseError(response, 'Error'));
    } catch (e) {
      return Error(e.toString());
    }
  }

  Future<Resource<bool>> deleteAttributeValue(int id) async {
    try {
      final token = await _getToken();
      final url = Uri.https(_baseHost, '/api/admin/attribute-values/$id');
      final response = await http.delete(url, headers: _headers(token));
      if (response.statusCode == 200 || response.statusCode == 201) return Success(true);
      return Error(_parseError(response, 'Error'));
    } catch (e) {
      return Error(e.toString());
    }
  }
}
