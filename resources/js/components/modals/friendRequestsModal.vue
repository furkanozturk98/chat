<template>
  <div>
    <b-modal
      ref="friend-requests"
      title="Friend Requests"
      size="lg"
    >
      <div v-if="friendRequests && friendRequests.length > 0">
        <div v-for="friendRequest in friendRequests">
          <div class="friend-drawer ">
            <img
              class="profile-image"
              src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/optimus-prime.jpeg"
              alt=""
            >
            <div class="text">
              <h6 v-if="friendRequest.from.id === 1">
                {{ friendRequest.to.name }}
              </h6>
              <h6 v-else>
                {{ friendRequest.from.name }}
              </h6>

              <p v-if="friendRequest.from.id === 1" class="text-muted">
                {{ friendRequest.to.about }}
              </p>
              <p v-else class="text-muted">
                {{ friendRequest.from.about }}
              </p>
            </div>
            <span class="ml-5">
              <div v-if="friendRequest.from.id === 1">
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
    export default {
        name: 'FriendRequests',
        props : ['currentUser'],

        data(){
            return {
                friendRequests : null
            }
        },

        mounted() {
            this.$eventHub.$on('showFriendRequestsModal',this.showFriendRequestsModal);

        },

        methods: {
            showFriendRequestsModal(friendRequests) {
                this.friendRequests = friendRequests;

                this.$refs['friend-requests'].show();
            },

            cancel(id){
                this.$http.delete('/api/friend-request/cancel/'+id);

                this.$eventHub.$emit('refreshFriendRequests');

                this.$refs['friend-requests'].hide();

                Vue.$toast.open({
                    message: 'Friend Request Canceled!',
                    type: 'success',
                    position: 'top-right',
                    duration: 1000
                });
            },

            approve(id){
                this.$http.put('/api/friend-request/approve/'+id);

                this.$eventHub.$emit('refreshFriendRequests');

                this.$refs['friend-requests'].hide()

                Vue.$toast.open({
                    message: 'Friend Request Approved!',
                    type: 'success',
                    position: 'top-right',
                    duration: 1000
                });

            },

            reject(id){
                this.$http.put('/api/friend-request/reject/'+id);

                this.$eventHub.$emit('refreshFriendRequests');

                this.$refs['friend-requests'].hide()

                Vue.$toast.open({
                    message: 'Friend Request Rejected!',
                    type: 'success',
                    position: 'top-right',
                    duration: 1000
                });

            }

        }
    }
</script>

<style scoped>

</style>
