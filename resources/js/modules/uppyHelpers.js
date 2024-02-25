function addExistingFilesToUppy(existingImages, uppy) {
	console.log(existingImages);
	existingImages.forEach(image => {
		const file = {
			source: 'local',
			name: image.file_name,
			type: image.mime_type,
			data: {},
			size: image.size,
			meta: {
				...image,
			},
			remote: true,
			preview: image.preview_url,
		};

		uppy.addFile(file);
	});
}

export {
	addExistingFilesToUppy,
}
