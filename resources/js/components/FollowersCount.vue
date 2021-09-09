<template>
    <div>
        <strong v-text="followerText" :follower="follower"></strong>
        Followers
    </div>
</template>

<script>
export default {
    props: ["userId", "follows", "followers"],

    data: function() {
        return {
            dynamicfollowers: "",
            follower: this.followers
        };
    },
    mounted() {
        //console.log(this.followers);
        var ref = this;

        this.dynamicfollowers = this.$root.$on("follow_event", function(
            follower,
            status
        ) {
            //   console.log(follower);
            ref.follower = follower;
            // console.log("inside" + ref.follower);
        });
    },

    methods: {},
    computed: {
        followerText() {
            //  console.log(this.follower);
            return this.follower;
        }
    }
};
</script>
