import { computed } from 'vue';

function tinymceData(maxHeight = 250) {

    const apiKey = computed(() => import.meta.env.VITE_TINY_MCE_API_KEY);

    const options = {
        plugins: 'searchreplace autolink autosave save directionality code visualblocks visualchars image link media table charmap nonbreaking insertdatetime advlist lists wordcount help charmap emoticons',
        menubar: 'edit insert format table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | charmap emoticons | insertfile image media link',
        max_height: maxHeight,
    }

    return {
        apiKey,
        options,
    }
}


export {
    tinymceData
}
