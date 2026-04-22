class Department {
  int id;
  String name;
  String? image;

  Department({
    required this.id,
    required this.name,
    this.image,
  });

  String get imageUrl =>
      image != null ? 'https://mitaicr.com/${image!}' : '';

  factory Department.fromJson(Map<String, dynamic> json) => Department(
        id: json["id"] ?? 0,
        name: json["name"] ?? '',
        image: json["image"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "name": name,
        "image": image,
      };

  static List<Department> fromJsonList(List<dynamic> jsonList) {
    return jsonList.map((item) => Department.fromJson(item)).toList();
  }
}
