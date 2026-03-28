<?php

use Phinx\Seed\AbstractSeed;

class NoticiasSeeder extends AbstractSeed
{
    /**
     * Run the seed for default news
     */
    public function run()
    {
        $data = [
            [
                'titulo'    => 'Festival Andino abre convocatoria para artistas regionales',
                'categoria' => 'Cultura',
                'resumen'   => 'El evento más importante del norte grande busca nuevos talentos para su edición 2026. Se recibirán propuestas hasta mayo.',
                'contenido' => 'El Festival Andino, que cada año reúne a miles de personas en torno a la música y las artes tradicionales, ha anunciado el inicio de su proceso de convocatoria. Según informaron los organizadores, este año se priorizará la participación de bandas locales y agrupaciones de danza andina. Las inscripciones se realizan directamente en el sitio oficial del festival.',
                'imagen'    => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=800&q=80',
                'autor'     => 1,
                'activo'    => 1,
                'creado_en' => date('Y-m-d H:i:s'),
            ],
            [
                'titulo'    => 'Precio del cobre sube 2.3% en la semana impulsado por demanda asiática',
                'categoria' => 'Economía',
                'resumen'   => 'El "sueldo de Chile" cierra una semana positiva en la Bolsa de Metales de Londres, alcanzando máximos de los últimos seis meses.',
                'contenido' => 'Una excelente noticia para la economía regional: el cobre ha mantenido una tendencia al alsa durante los últimos cinco días hábiles. Expertos aseguran que la reactivación industrial en Asia y la baja en los inventarios globales son los principales motores de este incremento, que beneficia directamente a los proyectos mineros locales.',
                'imagen'    => 'https://images.unsplash.com/photo-1542332213-31f87348057f?w=800&q=80',
                'autor'     => 1,
                'activo'    => 1,
                'creado_en' => date('Y-m-d H:i:s'),
            ],
            [
                'titulo'    => 'Nuevos proyectos mineros prometen reactivación en la zona',
                'categoria' => 'Economía',
                'resumen'   => 'Se estima una inversión superior a los 500 millones de dólares en los próximos dos años para la expansión de faenas.',
                'contenido' => 'El Gobierno Regional ha confirmado la aprobación ambiental de dos nuevos proyectos de expansión minera. Estas iniciativas no solo significan una inyección de capital a la zona, sino también la creación de más de 2.000 puestos de trabajo directos. Las autoridades locales enfatizaron que se exigirá el cumplimiento de estrictos estándares ambientales.',
                'imagen'    => 'https://images.unsplash.com/photo-1516937941344-00b4e0337589?w=800&q=80',
                'autor'     => 1,
                'activo'    => 1,
                'creado_en' => date('Y-m-d H:i:s'),
            ],
            [
                'titulo'    => 'Salud Pública inicia campaña regional contra enfermedades de invierno',
                'categoria' => 'Salud',
                'resumen'   => 'Hospitales y consultorios contarán con stock reforzado de vacunas y personal adicional para enfrentar los meses de frío.',
                'contenido' => 'Ante la llegada adelantada de bajas temperaturas, el Servicio de Salud ha lanzado oficialmente su Plan de Invierno. La meta es vacunar al 90% de la población de riesgo antes de que finalice el mes. Se han habilitado puntos móviles en ferias libres y centros comerciales para facilitar el acceso a la inmunización.',
                'imagen'    => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?w=800&q=80',
                'autor'     => 1,
                'activo'    => 1,
                'creado_en' => date('Y-m-d H:i:s'),
            ],
            [
                'titulo'    => 'Innovación: Colegios locales implementan robótica en sus aulas',
                'categoria' => 'Tecnología',
                'resumen'   => 'Estudiantes de enseñanza media desarrollan prototipos automatizados para resolver problemas de riego en zonas áridas.',
                'contenido' => 'Lo que comenzó como un taller extra programático se ha convertido en parte del currículum escolar. Diez establecimientos de la región han recibido kits de robótica avanzada. Los alumnos ya están trabajando en proyectos innovadores, demostrando que el talento tecnológico no tiene límites geográficos.',
                'imagen'    => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800&q=80',
                'autor'     => 1,
                'activo'    => 1,
                'creado_en' => date('Y-m-d H:i:s'),
            ],
        ];

        $posts = $this->table('noticias');
        $posts->insert($data)
              ->saveData();
    }
}
