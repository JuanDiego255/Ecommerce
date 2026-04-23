import 'package:ecommerce_flutter/src/data/dataSource/local/TenantSession.dart';

class ApiConfig {
  /// Active tenant host — set from the login server-config step.
  static String get BASE_URL => TenantSession.host;

  /// Alias kept so legacy services compile without change.
  static String get API_ECOMMERCE => TenantSession.host;

  /// Privacy policy URL — update once you host the document.
  static const String PRIVACY_POLICY_URL =
      'https://mitaicr.com/privacidad';
}
