<?php

$functions->redirect($system->url());
$log->logThis($log->logTypes["GALLERY"], $system->route(1));
$metaTag->title = $lang["gallery"];
if (count($route) === 1) {
	$id = 0;
	$type = 1;
} else {
	$end = end($route);
	//gelen linki - ile parçaladık ama içeriğin başlığı deneme-içerik gibi olabilir o yüzden id yi içinden alıp son elemanı silip diğerlerni bilirleştirelim
	$content_link = explode("-", $end);
	$id = end($content_link);

}
[$gallery_type, $gallery, $image_result] = $siteManager->galleryGet($id);


if ($gallery_type === 1) {
	require $system->view('gallery');
} else {
	[$video_count, $video_data] = $siteManager->getGalleryVideos($gallery->id);
	require $system->view('gallery-detail');
}
