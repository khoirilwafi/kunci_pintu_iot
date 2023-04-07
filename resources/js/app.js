import "./bootstrap";

const channel = Echo.channel("public.testing");

channel.subscribed(() => {
    console.log("subscribe done");
});
