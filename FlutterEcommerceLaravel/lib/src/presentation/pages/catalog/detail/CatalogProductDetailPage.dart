import 'package:cached_network_image/cached_network_image.dart';
import 'package:ecommerce_flutter/src/domain/models/catalog/CatalogProduct.dart';
import 'package:flutter/material.dart';
import 'package:url_launcher/url_launcher.dart';

const _kPrimary  = Color(0xFF2D2D2D);
const _kAccent   = Color(0xFF8B6F47);
const _kBg       = Color(0xFFFAFAFA);
const _kSub      = Color(0xFF757575);
const _kDivider  = Color(0xFFEEEEEE);

class CatalogProductDetailPage extends StatelessWidget {
  const CatalogProductDetailPage({super.key});

  @override
  Widget build(BuildContext context) {
    final args = ModalRoute.of(context)!.settings.arguments as Map<String, dynamic>;
    final product = args['product'] as CatalogProduct;
    return _CatalogProductDetailView(product: product);
  }
}

class _CatalogProductDetailView extends StatelessWidget {
  final CatalogProduct product;

  const _CatalogProductDetailView({required this.product});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: _kBg,
      body: CustomScrollView(
        slivers: [
          _buildAppBar(context),
          SliverToBoxAdapter(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildImage(),
                _buildInfo(context),
                const SizedBox(height: 100),
              ],
            ),
          ),
        ],
      ),
      bottomNavigationBar: _buildBottomBar(context),
    );
  }

  SliverAppBar _buildAppBar(BuildContext context) => SliverAppBar(
        pinned: true,
        backgroundColor: Colors.white,
        elevation: 0,
        scrolledUnderElevation: 1,
        shadowColor: _kDivider,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new, size: 18, color: _kPrimary),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          product.name,
          style: const TextStyle(
            fontSize: 15,
            fontWeight: FontWeight.w700,
            color: _kPrimary,
          ),
          maxLines: 1,
          overflow: TextOverflow.ellipsis,
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.share_outlined, color: _kPrimary, size: 20),
            onPressed: () => _shareWhatsApp(),
          ),
        ],
      );

  Widget _buildImage() {
    return AspectRatio(
      aspectRatio: 1.0,
      child: product.imageUrl.isNotEmpty
          ? CachedNetworkImage(
              imageUrl: product.imageUrl,
              fit: BoxFit.cover,
              placeholder: (_, __) => Container(color: const Color(0xFFF5F5F5)),
              errorWidget: (_, __, ___) => _imagePlaceholder(),
            )
          : _imagePlaceholder(),
    );
  }

  Widget _imagePlaceholder() => Container(
        color: const Color(0xFFF5F5F5),
        child: const Center(
          child: Icon(Icons.image_outlined, size: 64, color: Color(0xFFBDBDBD)),
        ),
      );

  Widget _buildInfo(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            product.name,
            style: const TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.w700,
              color: _kPrimary,
            ),
          ),
          if (product.code != null && product.code!.isNotEmpty) ...[
            const SizedBox(height: 4),
            Text(
              'Ref: ${product.code}',
              style: const TextStyle(fontSize: 12, color: _kSub),
            ),
          ],
          const SizedBox(height: 16),
          _buildPrice(),
          if (product.availableAttrs.isNotEmpty) ...[
            const SizedBox(height: 16),
            const Divider(color: _kDivider),
            const SizedBox(height: 12),
            const Text(
              'Variantes disponibles',
              style: TextStyle(
                fontSize: 13,
                fontWeight: FontWeight.w600,
                color: _kPrimary,
              ),
            ),
            const SizedBox(height: 8),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: product.availableAttrs
                  .map((a) => Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          border: Border.all(color: _kDivider),
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          a,
                          style: const TextStyle(fontSize: 12, color: _kPrimary),
                        ),
                      ))
                  .toList(),
            ),
          ],
          if (product.manageStock == 1) ...[
            const SizedBox(height: 16),
            const Divider(color: _kDivider),
            const SizedBox(height: 12),
            Row(
              children: [
                Icon(
                  product.totalStock > 0
                      ? Icons.check_circle_outline
                      : Icons.cancel_outlined,
                  size: 16,
                  color: product.totalStock > 0
                      ? const Color(0xFF43A047)
                      : const Color(0xFFE53935),
                ),
                const SizedBox(width: 6),
                Text(
                  product.totalStock > 0
                      ? 'En stock (${product.totalStock} disponibles)'
                      : 'Sin stock',
                  style: TextStyle(
                    fontSize: 13,
                    color: product.totalStock > 0
                        ? const Color(0xFF43A047)
                        : const Color(0xFFE53935),
                  ),
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildPrice() {
    if (product.hasDiscount) {
      return Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            '₡${_fmt(product.price)}',
            style: const TextStyle(
              fontSize: 14,
              color: _kSub,
              decoration: TextDecoration.lineThrough,
            ),
          ),
          Row(
            children: [
              Text(
                '₡${_fmt(product.finalPrice)}',
                style: const TextStyle(
                  fontSize: 26,
                  fontWeight: FontWeight.w700,
                  color: Color(0xFFE53935),
                ),
              ),
              const SizedBox(width: 10),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: const Color(0xFFE53935),
                  borderRadius: BorderRadius.circular(6),
                ),
                child: Text(
                  '-${product.discount}%',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 12,
                    fontWeight: FontWeight.w700,
                  ),
                ),
              ),
            ],
          ),
          Text(
            'Ahorra ₡${_fmt(product.savedAmount)}',
            style: const TextStyle(fontSize: 12, color: Color(0xFF43A047)),
          ),
        ],
      );
    }

    return Text(
      '₡${_fmt(product.price)}',
      style: const TextStyle(
        fontSize: 26,
        fontWeight: FontWeight.w700,
        color: _kAccent,
      ),
    );
  }

  Widget _buildBottomBar(BuildContext context) {
    return SafeArea(
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
        decoration: const BoxDecoration(
          color: Colors.white,
          border: Border(top: BorderSide(color: _kDivider)),
        ),
        child: Row(
          children: [
            Expanded(
              child: ElevatedButton.icon(
                style: ElevatedButton.styleFrom(
                  backgroundColor: const Color(0xFF25D366),
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 14),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(12)),
                ),
                icon: const Icon(Icons.chat_outlined, size: 18),
                onPressed: _shareWhatsApp,
                label: const Text(
                  'Consultar por WhatsApp',
                  style: TextStyle(fontWeight: FontWeight.w600),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _shareWhatsApp() async {
    final price = product.hasDiscount ? product.finalPrice : product.price;
    final text = Uri.encodeComponent(
      'Hola! Me interesa este producto:\n'
      '*${product.name}*\n'
      'Precio: ₡${_fmt(price)}\n'
      '${product.imageUrl}',
    );
    final uri = Uri.parse('https://wa.me/?text=$text');
    if (await canLaunchUrl(uri)) {
      await launchUrl(uri, mode: LaunchMode.externalApplication);
    }
  }

  String _fmt(double v) {
    if (v == v.truncate()) return v.toInt().toString();
    return v.toStringAsFixed(2);
  }
}
