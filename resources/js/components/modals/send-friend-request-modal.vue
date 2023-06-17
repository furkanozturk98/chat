<template>
  <div>
    <b-modal
      ref="add-person"
      title="Add Friend"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.prevent="submit">
        <b-form-group
          label="Email"
          label-for="email-input"
          :invalid-feedback="form.errors.first('email')"
        >
          <b-form-input
            id="email-input"
            v-model="form.email"
            :state="form.errors.has('email') ? false : null"
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
name: 'SendFriendRequestModal',

    data(){
        return {
            form: new Form({
                    email: null
                },
            ),
        }
    },

    mounted(){
        this.$eventHub.$on('showAddFriendModal',this.showAddFriendModal);
    },

    methods: {

        showAddFriendModal(){
            this.$refs['add-person'].show();
        },

        async submit() {
            try {
                const response = await this.form.post('/api/friend-requests');

                this.$refs['add-person'].hide();

                this.$eventHub.$emit('friendRequestSent', response.data);

                Vue.$toast.open({
                    message: 'Friend Request is sent!',
                    type: 'success',
                    position: 'top-right',
                    duration: 600
                });
            }
            catch (e) {
                if(e.response.status !== 422) {
                    Vue.$toast.open({
                        message: 'An error occurred!',
                        type: 'error',
                        position: 'top-right',
                        duration: 600
                    });
                }
            }
        },

        resetModal() {
            this.form.email = ''
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();

            this.submit();
        },
    }
}
</script>

