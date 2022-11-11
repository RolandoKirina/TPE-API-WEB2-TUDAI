# API REST para el recurso de  reseñas de chocolates.
Una sencilla API REST de reseñas de chocolates.
# Importar la base de datos
- importar desde PHPMyAdmin (o cualquiera) database/tpe.sql
# Pueba con postman, o similar.
El endpoint de la API es: http://localhost/tucarpetalocal/chocolate-rest/api/reviews
# Obtener todas las reseñas:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews.
# Obtener una reseña:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews/id de reseña
# Solo ordenar descendentemente por id:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews?order=desc
# Crear una nueva reseña :
 verbo http POST + http://localhost/tucarpetalocal/chocolate-rest/api/reviews
Agregar BODY
Ejemplo:
{
  "id_review": 12,
  "review": "string",
  "score": 1,
  "id_item": 2
}



# Eliminar una reseña:
 verbo http DELETE + http://localhost/tucarpetalocal/chocolate-rest/api/reviews/id
# Solo Filtrar por puntuacion:
 verbo http GET + http://localhost/tucarpetalocal/chocolate-rest/api/reviews?filter=algodepuntuacion
# Solo Paginar:
 verbo http GET + http://localhost/tucarpetalocal/chocolate-rest/api/reviews?page=numero&limit=numero
# Filtrar, paginar, ordenar :
  verbo http GET + http://localhost/projects/chocolate_rest/api/reviews?filter=numero&sortby=campo&order=elquequieras&page=numero&limit=loquequieras
# Filtrar y ordenar :
  verbo http GET + http://localhost/projects/chocolate_rest/api/reviews?filter=numerodepuntuacion&sortby=campo&order=elquequieras
# Filtrar y paginar :
  verbo http GET +http://localhost/projects/chocolate_rest/api/reviews?filter=numerodepuntuacion&page=numero&limit=numero
# Ordenar y paginar : 
 verbo http GET + http://localhost/projects/chocolate_rest/api/reviews?sortby=campo&order=asc/desc&page=numero&limit=numero






