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
          :invalid-feedback="form.errors.first('content')"
        >
          <b-form-input
            id="message-input"
            v-model="form.content"
            :state="form.errors.has('content') ? false : null"
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

name: 'EditGroupMessageModal',

    data() {
        return {
            form: new Form({
                content: null
                },
            ),
            data: null
        }
    },

    mounted() {
        this.$eventHub.$on('showEditGroupMessageModal', this.showEditGroupMessageModal);
    },

    methods: {

        showEditGroupMessageModal(data) {
            console.log(data);
            this.$refs['edit-message'].show();
            this.form.content = data.message;
            this.data = data;
        },

        async submit() {
            const data = {
                'id' : this.data.id,
                'message' : this.form.content
            }

            await this.form.put('/api/group-messages/' + this.data.id)
                .then(() => {

                    Vue.$toast.open({
                        message: 'Message is edited successfully',
                        type: 'success',
                        position: 'top-right',
                        duration: 600
                    });

                    this.$eventHub.$emit('groupMessageEdited',data);
                })
                .catch(() => {
                    Vue.$toast.open({
                        message: 'An error occurred.',
                        type: 'error',
                        position: 'top-right',
                        duration: 1000
                    });
                });

            this.$refs['edit-message'].hide();
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
