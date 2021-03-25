<template>
  <div>
    <div v-for="item in items" @click="friendClick(item)">
      <div class="friend-drawer friend-drawer--onhover" :class="{'friend-dark' : nightMode}">
        <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/robocop.jpg" alt="">
        <div class="text" :class=" {'text-white' : nightMode}">
          <h6>{{ item.friend.name }}</h6>
          <p :class="{'text-muted' :!nightMode, 'text-light' :nightMode,}">
            {{ item.friend.about }}
          </p>
        </div>
        <span class="time text-muted small">13:21</span> <!-- last message send  -->
      </div>
    </div>
    <!--    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/optimus-prime.jpeg" alt="">
      <div class="text">
        <h6>Optimus</h6>
        <p class="text-muted">
          Wanna grab a beer?
        </p>
      </div>
      <span class="time text-muted small">00:32</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover ">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/real-terminator.png" alt="">
      <div class="text">
        <h6>Skynet</h6>
        <p class="text-muted">
          Seen that canned piece of s?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/termy.jpg" alt="">
      <div class="text">
        <h6>Termy</h6>
        <p class="text-muted">
          Im studying spanish...
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rick.jpg" alt="">
      <div class="text">
        <h6>Richard</h6>
        <p class="text-muted">
          I'm not sure...
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>
    <div class="friend-drawer friend-drawer&#45;&#45;onhover">
      <img class="profile-image" src="https://www.clarity-enhanced.net/wp-content/uploads/2020/06/rachel.jpeg" alt="">
      <div class="text">
        <h6>XXXXX</h6>
        <p class="text-muted">
          Hi, wanna see something?
        </p>
      </div>
      <span class="time text-muted small">13:21</span>
    </div>-->
  </div>
</template>

<script>
    export default {
        name: 'ChatFriendList',
        props : ['nightMode'],

        data (){
            return {
                items : []
            }
        },

        mounted() {
            this.getFriends();
        },

        methods: {
            async getFriends(){
                const response = await this.$http.get('/api/friend-list');
                this.items = response.data.data;
                console.log(this.items);
            },

            async friendClick(item){
                this.$eventHub.$emit('friendClick',item);
                console.log(item);
            }
        }
    }
</script>

<style scoped>

</style>
