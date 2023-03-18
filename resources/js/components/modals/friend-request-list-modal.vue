<template>
  <div>
    <b-modal
      ref="friend-requests"
      title="Friend Requests"
      size="lg"
      hide-footer
    >
      <div v-if="friendRequests && friendRequests.length > 0">
        <div v-for="friendRequest in friendRequests" :key="friendRequest.id">
          <div class="friend-drawer ">
            <img
              class="profile-image"
              src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/optimus-prime.jpeg"
              alt=""
            >
            <div class="text">
              <h6 v-if="friendRequest.from.id === currentUser.id">
                {{ friendRequest.to.name }}
              </h6>
              <h6 v-else>
                {{ friendRequest.from.name }}
              </h6>

              <p v-if="friendRequest.from.id === currentUser.id" class="text-muted">
                {{ friendRequest.to.about }}
              </p>
              <p v-else class="text-muted">
                {{ friendRequest.from.about }}
              </p>
            </div>
            <span class="ml-5">
              <div v-if="friendRequest.from.id === currentUser.id">
                <button class="btn btn-outline-secondary" @click="cancel(friendRequest.id)">Cancel</button>
              </div>

              <div v-else>
                <button class="btn btn-outline-success" @click="approve(friendRequest.id)"><b-icon
                  icon="check-circle"
                /></button>
                <button class="btn btn-outline-danger" @click="reject(friendRequest.id)"><b-icon
                  icon="x-circle"
                /></button>
              </div>
            </span>
          </div>
        </div>
      </div>

      <div v-else>
        <div class="alert alert-info" role="alert">
          There is no friend request yet.
        </div>
      </div>
    </b-modal>
  </div>
</template>

<script>
import Form from 'form-backend-validation';

export default {
    name: 'FriendRequests',
    props: ['currentUser'],

    data() {
        return {
            friendRequests: [],
            form: new Form({},
            ),
        }
    },

    mounted() {
        this.$eventHub.$on('showFriendRequestsModal', this.showFriendRequestsModal);

        this.$eventHub.$on('friendRequestSent', this.updateFriendRequests);

    },

    methods: {

        async fetch() {
            const response = await this.$http.get('/api/get-friend-requests');
            this.friendRequests = response.data.data;
        },

        showFriendRequestsModal() {
            this.fetch();

            this.$refs['friend-requests'].show();
        },

        updateFriendRequests(data){
          this.friendRequests.push(data);
        },

        cancel(id) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to cancel this friend request.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.$http.delete('/api/friend-request/cancel/' + id);

                        this.$eventHub.$emit('refreshFriendRequests');

                        this.$refs['friend-requests'].hide();

                        Vue.$toast.open({
                            message: 'Friend Request Canceled!',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });
                    }
                });
        },

        async approve(id) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to approve this friend request.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                       this.approveRequest(id);

                        this.$refs['friend-requests'].hide()

                        Vue.$toast.open({
                            message: 'Friend Request Approved!',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });
                    }
                });
        },

        async approveRequest(id){
            const response = await this.form.put('/api/friend-request/approve/' + id);

            this.$eventHub.$emit('friendRequestApproved', response.data);
        },

        reject(id) {
            this.$bvModal.msgBoxConfirm('Please confirm that you want to reject this friend request.', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        this.$http.put('/api/friend-request/reject/' + id);

                        this.$eventHub.$emit('refreshFriendRequests');

                        this.$refs['friend-requests'].hide()

                        Vue.$toast.open({
                            message: 'Friend Request Rejected!',
                            type: 'success',
                            position: 'top-right',
                            duration: 1000
                        });
                    }
                });
        }
    }
}
</script>

<style scoped>

</style>
