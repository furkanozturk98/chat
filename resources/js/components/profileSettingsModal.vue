<template>
    <b-modal
        ref="profile-settings"
        title="Profile Settings"
        size="lg"
        @ok="handleOk"
    >
        <div v-if="currentUser">
            <form ref="form" @submit.prevent="submit" enctype='multipart/form-data'>

                <img :src="urlForPreview ? urlForPreview : '/images/'+currentUser.image "
                     class="rounded-circle w-25 mb-5 mx-auto d-block"/>


                <b-form-group
                    label="Image"
                    label-for="image-input"
                    :invalid-feedback="form.errors.first('image')"
                >
                    <b-form-file
                        id="image-input"
                        v-model="form.image"
                        :state="form.errors.has('image') ? false : null"
                        placeholder="Choose a file or drop it here..."
                        drop-placeholder="Drop file here..."
                        @change="onFileChange"
                        accept=".jpg, .png"
                    ></b-form-file>
                </b-form-group>


                <b-form-group
                    label="Name"
                    label-for="name-input"
                    :invalid-feedback="form.errors.first('name')"
                >
                    <b-form-input
                        id="name-input"
                        v-model="form.name"
                        :state="form.errors.has('name') ? false : null"
                        required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                    label="About"
                    label-for="about-input"
                    :invalid-feedback="form.errors.first('about')"
                >
                    <b-form-input
                        id="about-input"
                        v-model="form.about"
                        :state="form.errors.has('about') ? false : null"
                        required
                    ></b-form-input>
                </b-form-group>

            </form>
        </div>
    </b-modal>

</template>

<script>
    import Form from 'form-backend-validation';

    export default {
        name: 'profileSettingsModal',
        props: ['currentUser'],

        data() {
            return {
                form: new Form({
                        image: null,
                        name: this.currentUser.name,
                        about: this.currentUser.about
                    },
                ),
                urlForPreview: null,
            }
        },

        mounted() {

            this.$eventHub.$on('showProfileSettingsModal', this.showProfileSettingsModal);
        },

        methods: {
            showProfileSettingsModal() {
                this.$refs['profile-settings'].show();
            },

            async submit() {

                await this.form.post('/api/profile-setting');

                this.$refs['profile-settings'].hide();

                Vue.$toast.open({
                    message: 'Settings Updated!',
                    type: 'success',
                    position: 'top-right',
                    duration: 600
                });
            },

            onFileChange(e) {
                const file = e.target.files[0];
                this.urlForPreview = URL.createObjectURL(file);
            },

            handleOk(bvModalEvt) {
                bvModalEvt.preventDefault();

                this.submit();
            },
        }
    }
</script>

<style scoped>

</style>
