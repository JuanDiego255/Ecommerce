import 'dart:convert';

class TenantConfig {
  final String domain;   // e.g. "mitaicr.com"
  final String name;     // Display name, e.g. "Mitaï CR"
  final String appToken; // Pre-shared token generated from the web admin

  const TenantConfig({
    required this.domain,
    required this.name,
    required this.appToken,
  });

  factory TenantConfig.fromJson(Map<String, dynamic> j) => TenantConfig(
        domain: j['domain'] as String? ?? '',
        name: j['name'] as String? ?? '',
        appToken: j['app_token'] as String? ?? '',
      );

  Map<String, dynamic> toJson() => {
        'domain': domain,
        'name': name,
        'app_token': appToken,
      };

  @override
  String toString() => 'TenantConfig(domain: $domain, name: $name)';
}
