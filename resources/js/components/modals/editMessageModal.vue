<template>
  <div>
    <b-modal
      ref="edit-message"
      title="Edit Message"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.prevent="submit">
        <b-form-group
          label="Message"
          label-for="message-input"
          :invalid-feedback="form.errors.first('message')"
        >
          <b-form-input
            id="message-input"
            v-model="form.message"
            :state="form.errors.has('message') ? false : null"
            required
          />
        </b-form-group>
      </form>
    </b-modal>
  </div>
</template>

<script>
import Form from 'form-backend-validation';

export default {
name: 'EditMessageModal',

    data(){
        return {
            form: new Form({
                    message: null
                },
            ),
        }
    },

    mounted(){
        this.$eventHub.$on('showEditMessageModal',this.showEditMessageModal);
    },

    methods: {

        showEditMessageModal(){
            this.$refs['edit-message'].show();
        },

        async submit() {
           /* await this.form.post('/api/friend-request');

            this.$refs['add-person'].hide();

            this.getFriendRequests();

            Vue.$toast.open({
                message: 'Friend Request is sent!',
                type: 'success',
                position: 'top-right',
                duration: 600
            });*/
        },

        async getFriendRequests() {
          /*  const response = await this.$http.get('/api/get-friend-requests');
            this.friendRequest = response.data.data;*/
        },

        resetModal() {
            this.form.message = ''
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
