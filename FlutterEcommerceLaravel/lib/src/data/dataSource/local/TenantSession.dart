import 'dart:convert';
import 'package:ecommerce_flutter/src/domain/models/TenantConfig.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Singleton that holds the active tenant configuration in memory.
/// Call [initialize] once at app startup (in main).
class TenantSession {
  TenantSession._();

  static const String _kKey = 'tenant_config_v1';

  static TenantConfig? _config;

  // ─── Bootstrap ────────────────────────────────────────────────────────────

  static Future<void> initialize() async {
    final prefs = await SharedPreferences.getInstance();
    final raw = prefs.getString(_kKey);
    if (raw != null) {
      try {
        _config = TenantConfig.fromJson(json.decode(raw) as Map<String, dynamic>);
      } catch (_) {
        _config = null;
      }
    }
  }

  // ─── Persist ──────────────────────────────────────────────────────────────

  static Future<void> save(TenantConfig config) async {
    _config = config;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_kKey, json.encode(config.toJson()));
  }

  /// Clears tenant config AND the user auth session (tokens become invalid).
  static Future<void> clear() async {
    _config = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove(_kKey);
    await prefs.remove('user'); // invalidate any stored auth token
  }

  // ─── Accessors ────────────────────────────────────────────────────────────

  static bool get isConfigured => _config != null && _config!.domain.isNotEmpty;

  /// The tenant's API host, e.g. "mitaicr.com".
  static String get host => _config?.domain ?? '';

  /// Human-readable tenant name shown in the UI.
  static String get displayName => _config?.name ?? '';

  /// Pre-shared app token sent as [X-App-Token] header on every request.
  static String? get appToken => _config?.appToken;

  static TenantConfig? get config => _config;
}
