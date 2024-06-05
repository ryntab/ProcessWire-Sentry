<template>
    <Icon :name="platformIcon" color="black" size="25" />
</template>
<script>
export default {
    props: {
        platform: {
            type: String,
            required: true
        }
    },
    computed: {
        platformIcon() {
            switch (this.platform) {
                case 'php':
                    return 'material-symbols:php';
                case 'GitLab':
                    return 'uil:gitlab';
                case 'Bitbucket':
                    return 'uil:bitbucket';
                case 'GitLab':
                    return 'uil:gitlab';
                default:
                    return 'uil:user';
            }
        }
    }
}
</script>