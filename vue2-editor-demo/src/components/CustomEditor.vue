<template>
    <vue-editor
            :customModules="customModulesForEditor"
            :editorOptions="editorSettings"
            v-model="content">
    </vue-editor>
</template>

<script>
    import {VueEditor} from 'vue2-editor'
    import ImageResize from '@appsflare/quill-image-resize-module'
    import {ImageUpload} from 'quill-image-upload'

    export default {
        components: {
            VueEditor
        },
        data() {
            return {
                content: '',
                customModulesForEditor: [
                    {alias: 'imageResize', module: ImageResize},
                    {alias: 'imageUpload', module: ImageUpload}
                ],
                editorSettings: {
                    modules: {
                        imageResize: {
                            modules: ['Resize', 'DisplaySize', 'Toolbar']
                        },
                        imageUpload: {
                            url: "http://localhost/vue2-editor/vue2-server-image-upload/image.php",
                            method: "POST",
                            callbackOK: (serverResponse, next) => {
                                next(serverResponse);
                            },
                            checkBeforeSend: (file, next) => {
                                next(file);
                            }
                        }
                    }
                }
            }
        },
    }
</script>