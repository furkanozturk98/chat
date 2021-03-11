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
                    ></b-form-input>
                </b-form-group>

            </form>
        </b-modal>

        <friend-request-modal :current-user="currentUser"></friend-request-modal>
        <profile-settings-modal :current-user="currentUser"></profile-settings-modal>

        <div class="row no-gutters">
            <div class="col-md-3 border-right">

                <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
                    <img class="profile-image"
                         src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/filip.jpg" alt="Profile img">
                    <span class="settings-tray--right">

			<i class="material-icons">cached</i>
			<i @click=showAddPersonModal() class="material-icons">person_add</i>
            <i class="material-icons" @click="toggleDropDown">menu</i>

                    <b-dropdown ref="dropdown" size="lg" variant="link" toggle-class="text-decoration-none" no-caret>
                        <b-dropdown-item @click="showFriendRequestsModal()">Friend requests</b-dropdown-item>
                        <b-dropdown-item @click="showProfileSettingsModal()">Profile Settings</b-dropdown-item>
                        <b-dropdown-item @click="nightModeOn()">Night Mode</b-dropdown-item>

                    </b-dropdown>

		  </span>


                </div>

                <div class="search-box" :class="{'search-box-dark': nightMode }">
                    <div class="input-wrapper" :class="{'input-wrapper-dark': nightMode}">
                        <i class="material-icons">search</i>
                        <input placeholder="Search here" type="text">
                    </div>
                </div>

                <div class="messages overflow-auto" style="height:80%;">
                    <chat-friend-list :night-mode="nightMode"></chat-friend-list>
                </div>

            </div>
            <div class="col-md-9">
                <div class="settings-tray" :class="{'settings-tray-dark': nightMode}">
                    <div class="friend-drawer no-gutters"
                         :class="{ 'friend-drawer--grey' : !nightMode, 'friend-drawer--dark' : nightMode }">
                        <img class="profile-image"
                             src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
                        <div class="text" :class=" {'text-white' : nightMode}">
                            <h6>Robo Cop</h6>
                            <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
                                Layin' down the law since like before Christ...
                            </p>
                        </div>
                        <span class="settings-tray--right">
			  <i class="material-icons">cached</i>
			  <i class="material-icons">message</i>
			  <i class="material-icons">menu</i>
			</span>
                    </div>
                </div>

                <div class="chat-panel" :class="{ 'chat-panel-dark': nightMode } ">

                    <div class="overflow-auto" style="height:600px;">
                        <chat-conversation-screen></chat-conversation-screen>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="">
                                <div class="chat-box-tray" :class="{ 'chat-box-tray-dark': nightMode}">
                                    <i class="material-icons">sentiment_very_satisfied</i>
                                    <input type="text" placeholder="Type your message here...">
                                    <i class="material-icons">mic</i>
                                    <i class="material-icons">send</i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';
    import ChatFriendList from "./chatFriendList";
    import ChatConversationScreen from "./chatConversationScreen";
    import FriendRequestModal from "./friendRequestsModal";
    import ProfileSettingsModal from "./profileSettingsModal";

    export default {
        name: "chatIndex",
        components: {ProfileSettingsModal, ChatConversationScreen, ChatFriendList, FriendRequestModal},
        props: ['currentUser'],

        data() {
            return {
                form: new Form({
                        email: null
                    },
                ),
                show: false,
                friendRequests: null,
                nightMode: false
            }
        },

        mounted() {
            this.getFriendRequests();

            this.$eventHub.$on('refreshFriendRequests', this.getFriendRequests);
        },
        methods: {

            async submit() {
                await this.form.post("/api/friend-request");

                this.$refs['add-person'].hide();

                this.getFriendRequests();
            },

            resetModal() {
                this.form.email = ''
            },

            handleOk(bvModalEvt) {
                bvModalEvt.preventDefault();

                this.submit();
            },

            toggleDropDown() {

                this.show = !this.show;

                if (!!this.show) this.$refs['dropdown'].show();
                else this.$refs['dropdown'].hide()

            },

            showAddPersonModal() {
                this.$refs['add-person'].show()
            },

            showFriendRequestsModal() {
                this.$eventHub.$emit('showFriendRequestsModal', this.friendRequest);
            },

            showProfileSettingsModal() {
                this.$eventHub.$emit('showProfileSettingsModal');
            },

            async getFriendRequests() {
                const response = await this.$http.get('/api/get-friend-requests');
                this.friendRequest = response.data.data;
            },

            nightModeOn(){
                if( this.nightMode === true){
                    this.nightMode = false;
                }
                else {
                    this.nightMode = true;
                }
            }
        }
    }
</script>
