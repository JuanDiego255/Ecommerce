class AdminOrderItem {
  final int id;
  final String productName;
  final int quantity;
  final double total;
  final String? imageUrl;
  final List<String> attributes;
  final int cancelItem;

  const AdminOrderItem({
    required this.id,
    required this.productName,
    required this.quantity,
    required this.total,
    this.imageUrl,
    required this.attributes,
    required this.cancelItem,
  });

  factory AdminOrderItem.fromJson(Map<String, dynamic> j) => AdminOrderItem(
        id: j['id'] ?? 0,
        productName: j['product_name'] ?? '',
        quantity: j['quantity'] ?? 0,
        total: (j['total'] as num?)?.toDouble() ?? 0.0,
        imageUrl: j['image_url'] as String?,
        attributes: (j['attributes'] as List<dynamic>? ?? [])
            .map((e) => e.toString())
            .toList(),
        cancelItem: j['cancel_item'] ?? 0,
      );
}

class AdminOrder {
  final int id;
  final String displayName;
  final String displayEmail;
  final String displayTelephone;
  final double totalBuy;
  final double totalDelivery;
  final double totalIva;
  final int approved;
  final int delivered;
  final int readyToGive;
  final int cancelBuy;
  final String kindOfBuy;
  final int apartado;
  final double montoApartado;
  final String? guideNumber;
  final String? detail;
  final String createdAt;
  // Detail-only shipping fields
  final String? sCountry;
  final String? sProvince;
  final String? sCity;
  final String? sDistrict;
  final String? sAddress;
  final List<AdminOrderItem> items;

  const AdminOrder({
    required this.id,
    required this.displayName,
    required this.displayEmail,
    required this.displayTelephone,
    required this.totalBuy,
    required this.totalDelivery,
    required this.totalIva,
    required this.approved,
    required this.delivered,
    required this.readyToGive,
    required this.cancelBuy,
    required this.kindOfBuy,
    required this.apartado,
    required this.montoApartado,
    this.guideNumber,
    this.detail,
    required this.createdAt,
    this.sCountry,
    this.sProvince,
    this.sCity,
    this.sDistrict,
    this.sAddress,
    this.items = const [],
  });

  // 'V' = Web, otherwise Interna/Apartado depending on apartado flag
  String get origin {
    if (kindOfBuy == 'V') return 'Web';
    return apartado == 1 ? 'Apartado' : 'Interna';
  }

  double get pendiente =>
      apartado == 1 ? (totalBuy - montoApartado).clamp(0, double.infinity) : 0;

  String get formattedDate {
    try {
      final dt = DateTime.parse(createdAt);
      final d = dt.day.toString().padLeft(2, '0');
      final m = dt.month.toString().padLeft(2, '0');
      final h = dt.hour.toString().padLeft(2, '0');
      final min = dt.minute.toString().padLeft(2, '0');
      return '$d/$m $h:$min';
    } catch (_) {
      return createdAt;
    }
  }

  AdminOrder copyWith({
    int? approved,
    int? delivered,
    int? readyToGive,
    int? cancelBuy,
    String? guideNumber,
    String? detail,
    double? montoApartado,
    List<AdminOrderItem>? items,
    String? sCountry,
    String? sProvince,
    String? sCity,
    String? sDistrict,
    String? sAddress,
  }) =>
      AdminOrder(
        id: id,
        displayName: displayName,
        displayEmail: displayEmail,
        displayTelephone: displayTelephone,
        totalBuy: totalBuy,
        totalDelivery: totalDelivery,
        totalIva: totalIva,
        approved: approved ?? this.approved,
        delivered: delivered ?? this.delivered,
        readyToGive: readyToGive ?? this.readyToGive,
        cancelBuy: cancelBuy ?? this.cancelBuy,
        kindOfBuy: kindOfBuy,
        apartado: apartado,
        montoApartado: montoApartado ?? this.montoApartado,
        guideNumber: guideNumber ?? this.guideNumber,
        detail: detail ?? this.detail,
        createdAt: createdAt,
        sCountry: sCountry ?? this.sCountry,
        sProvince: sProvince ?? this.sProvince,
        sCity: sCity ?? this.sCity,
        sDistrict: sDistrict ?? this.sDistrict,
        sAddress: sAddress ?? this.sAddress,
        items: items ?? this.items,
      );

  factory AdminOrder.fromJson(Map<String, dynamic> j) => AdminOrder(
        id: j['id'] ?? 0,
        displayName: j['display_name'] ?? 'Sin nombre',
        displayEmail: j['display_email'] ?? '',
        displayTelephone: j['display_telephone'] ?? '',
        totalBuy: (j['total_buy'] as num?)?.toDouble() ?? 0.0,
        totalDelivery: (j['total_delivery'] as num?)?.toDouble() ?? 0.0,
        totalIva: (j['total_iva'] as num?)?.toDouble() ?? 0.0,
        approved: j['approved'] ?? 0,
        delivered: j['delivered'] ?? 0,
        readyToGive: j['ready_to_give'] ?? 0,
        cancelBuy: j['cancel_buy'] ?? 0,
        kindOfBuy: j['kind_of_buy'] ?? '',
        apartado: j['apartado'] ?? 0,
        montoApartado: (j['monto_apartado'] as num?)?.toDouble() ?? 0.0,
        guideNumber: j['guide_number'] as String?,
        detail: j['detail'] as String?,
        createdAt: j['created_at'] ?? '',
        sCountry: j['s_country'] as String?,
        sProvince: j['s_province'] as String?,
        sCity: j['s_city'] as String?,
        sDistrict: j['s_district'] as String?,
        sAddress: j['s_address'] as String?,
        items: (j['items'] as List<dynamic>? ?? [])
            .map((e) => AdminOrderItem.fromJson(e as Map<String, dynamic>))
            .toList(),
      );

  static List<AdminOrder> fromJsonList(List<dynamic> list) =>
      list.map((e) => AdminOrder.fromJson(e as Map<String, dynamic>)).toList();
}
