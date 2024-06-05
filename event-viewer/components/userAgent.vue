<template>
    <Icon :name="platformIcon" color="black" size="30" />
</template>
<script>
export default {
    props: {
        userAgent: {
            type: String,
            required: true
        }
    },
    computed: {
        platformIcon() {
            switch (this.userAgent) {
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