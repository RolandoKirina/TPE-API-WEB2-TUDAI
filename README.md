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
# Crear una nueva reseña:
 verbo http POST + http://localhost/tucarpetalocal/chocolate-rest/api/reviews
# Eliminar una reseña:
 verbo http DELETE + http://localhost/tucarpetalocal/chocolate-rest/api/reviews/id
# Solo Filtrar por puntuacion:
 http://localhost/tucarpetalocal/chocolate-rest/api/reviews?filter=algodepuntuacion
# Solo Paginar:
http://localhost/tucarpetalocal/chocolate-rest/api/reviews?page=numero&limit=numero


#TODAS LAS COSAS A LA VEZ 
http://localhost/projects/chocolate-rest/api/reviews?filter=3&sortyby=id_review&order=desc&page=1&limit=3





