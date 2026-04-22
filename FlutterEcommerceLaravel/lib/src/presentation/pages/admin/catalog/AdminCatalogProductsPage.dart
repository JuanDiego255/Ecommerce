import 'package:ecommerce_flutter/src/data/dataSource/remote/services/MitaiApiService.dart';
import 'package:ecommerce_flutter/src/domain/models/MitaiProduct.dart';
import 'package:ecommerce_flutter/src/domain/utils/Resource.dart';
import 'package:ecommerce_flutter/src/presentation/pages/admin/catalog/AdminProductDetailPage.dart';
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';

const Color _kBg = Color(0xFFFAF8F5);
const Color _kPrimary = Color(0xFF8B6F47);
const Color _kAccent = Color(0xFFC8966A);
const Color _kSurface = Color(0xFFFFFFFF);
const Color _kTextPrimary = Color(0xFF1A1A1A);
const Color _kTextSecondary = Color(0xFF6B6B6B);
const Color _kStockOk = Color(0xFF22C55E);
const Color _kStockLow = Color(0xFFF59E0B);
const Color _kStockOut = Color(0xFFEF4444);

class AdminCatalogProductsPage extends StatefulWidget {
  final int categoryId;
  final String categoryName;

  const AdminCatalogProductsPage({
    super.key,
    required this.categoryId,
    required this.categoryName,
  });

  @override
  State<AdminCatalogProductsPage> createState() =>
      _AdminCatalogProductsPageState();
}

class _AdminCatalogProductsPageState extends State<AdminCatalogProductsPage> {
  final _api = MitaiApiService();
  final _currencyFormat = NumberFormat('#,###', 'es');

  bool _loading = true;
  String? _error;
  List<MitaiProduct> _products = [];

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    final result = await _api.getProductsByCategory(widget.categoryId);
    if (!mounted) return;
    if (result is Success<List<MitaiProduct>>) {
      setState(() {
        _products = result.data;
        _loading = false;
      });
    } else if (result is Error<List<MitaiProduct>>) {
      setState(() {
        _error = result.message;
        _loading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: _kBg,
      appBar: AppBar(
        title: Text(
          widget.categoryName,
          style: const TextStyle(
            color: _kTextPrimary,
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
        backgroundColor: _kSurface,
        elevation: 0,
        iconTheme: const IconThemeData(color: _kPrimary),
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, color: _kPrimary),
          onPressed: () => Navigator.pop(context),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.open_in_browser, color: _kPrimary),
            tooltip: 'Gestionar en web',
            onPressed: () => launchUrl(
              Uri.https('mitaicr.com', '/categories/${widget.categoryId}'),
              mode: LaunchMode.externalApplication,
            ),
          ),
        ],
      ),
      body: _buildBody(),
    );
  }

  Widget _buildBody() {
    if (_loading) {
      return const Center(child: CircularProgressIndicator(color: _kPrimary));
    }
    if (_error != null) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.error_outline, size: 64, color: Colors.red.shade300),
            const SizedBox(height: 16),
            Text(
              _error!,
              textAlign: TextAlign.center,
              style: const TextStyle(color: _kTextSecondary),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: _loadData,
              style: ElevatedButton.styleFrom(backgroundColor: _kPrimary),
              child: const Text('Reintentar',
                  style: TextStyle(color: Colors.white)),
            ),
          ],
        ),
      );
    }
    if (_products.isEmpty) {
      return const Center(
        child: Text('No hay productos en esta categoría',
            style: TextStyle(color: _kTextSecondary)),
      );
    }
    return RefreshIndicator(
      onRefresh: _loadData,
      color: _kPrimary,
      child: GridView.builder(
        padding: const EdgeInsets.all(16),
        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 2,
          crossAxisSpacing: 12,
          mainAxisSpacing: 12,
          childAspectRatio: 0.62,
        ),
        itemCount: _products.length,
        itemBuilder: (context, index) {
          final product = _products[index];
          return _ProductCard(
            product: product,
            currencyFormat: _currencyFormat,
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (_) => AdminProductDetailPage(
              product: product,
              categoryId: widget.categoryId,
            ),
                ),
              );
            },
          );
        },
      ),
    );
  }
}

class _ProductCard extends StatelessWidget {
  final MitaiProduct product;
  final NumberFormat currencyFormat;
  final VoidCallback onTap;

  const _ProductCard({
    required this.product,
    required this.currencyFormat,
    required this.onTap,
  });

  Color _stockColor(int? stock) {
    if (stock == null || stock == 0) return _kStockOut;
    if (stock <= 5) return _kStockLow;
    return _kStockOk;
  }

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        decoration: BoxDecoration(
          color: _kSurface,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.06),
              blurRadius: 8,
              offset: const Offset(0, 3),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Product image
            SizedBox(
              height: 130,
              child: ClipRRect(
                borderRadius:
                    const BorderRadius.vertical(top: Radius.circular(16)),
                child: product.imageUrl.isNotEmpty
                    ? Image.network(
                        product.imageUrl,
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => _placeholder(),
                      )
                    : _placeholder(),
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(8),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Product name
                    Text(
                      product.name,
                      style: const TextStyle(
                        color: _kTextPrimary,
                        fontWeight: FontWeight.bold,
                        fontSize: 12,
                      ),
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    // Price
                    Text(
                      '₡${currencyFormat.format(product.price)}',
                      style: const TextStyle(
                        color: _kPrimary,
                        fontWeight: FontWeight.w700,
                        fontSize: 13,
                      ),
                    ),
                    const Spacer(),
                    // Stock badge
                    if (product.manageStock == 1)
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 6, vertical: 2),
                        decoration: BoxDecoration(
                          color: _stockColor(product.totalStock)
                              .withOpacity(0.15),
                          borderRadius: BorderRadius.circular(6),
                        ),
                        child: Text(
                          product.totalStock != null
                              ? 'Stock: ${product.totalStock}'
                              : 'Sin stock',
                          style: TextStyle(
                            color: _stockColor(product.totalStock),
                            fontSize: 10,
                            fontWeight: FontWeight.w600,
                          ),
                        ),
                      ),
                    // Attribute chips
                    if (product.attrList.isNotEmpty) ...[
                      const SizedBox(height: 4),
                      Wrap(
                        spacing: 4,
                        runSpacing: 2,
                        children: product.attrList
                            .take(2)
                            .map(
                              (attr) => Container(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 5, vertical: 1),
                                decoration: BoxDecoration(
                                  color: const Color(0xFFF0EBE3),
                                  borderRadius: BorderRadius.circular(4),
                                ),
                                child: Text(
                                  attr.trim(),
                                  style: const TextStyle(
                                    color: _kAccent,
                                    fontSize: 9,
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                              ),
                            )
                            .toList(),
                      ),
                    ],
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _placeholder() {
    return Container(
      color: const Color(0xFFF0EBE3),
      child: const Center(
        child: Icon(Icons.inventory_2_outlined, size: 40, color: _kAccent),
      ),
    );
  }
}
