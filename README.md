# MailerLite Task

## steps to run the project

- Clone the repository
- Run `cp .env.example .env`
- Run `cp mailerlite-vue/.env.example mailerlite-vue/.env`
- Run `docker-compose up -d`
- Run `chmod -x bootstrap.sh`
- Run `sh bootstrap.sh`
- Run `docker exec -i mailerlite-app-fpm ./vendor/bin/phpstan` for static analysis
- Run `docker exec -i mailerlite-app-fpm ./vendor/bin/pest` for running tests
- Run `docker exec -i mailerlite-app-fpm ./vendor/bin/pest --coverage` for generating coverage report
- Open `http://localhost:5173` in your browser

## Questions

### That endpoint will be called about a million times a minute and some requests will be for the same subscriber, how would you scale it?**

- Get subscriber
    - We can use cache to store the subscriber data and use it to avoid querying
      the database for the same subscriber, additionally we can use index on the
      email column to make the query faster in case subscriber doesn't exist in
      cache.
- Store subscriber
    - At the time of storing subscriber we can check if that subscriber is
      present in cache, if not we can get the count of available subscribers by
      email, as we have index on email column it will be faster, otherwise we
      can return the error response, if the count is 0 we can store the
      subscriber in the database and cache it.

### How would you scale the above endpoints to handle 10 times the traffic? What challenges do you foresee?

There are multiple ways to scale the above endpoints, here are some general
steps and strategies we can employ

1. Load Balancing
    - We can use load balancing to distribute the traffic to multiple servers.
      This helps prevent a single server from becoming a bottleneck. We can use
      round-robin, least connections, or IP hash algorithms based on use-case.

2. Horizontal Scaling
    - We can use horizontal scaling to add more servers to handle the traffic.
      This helps to increase the capacity of the system. We can use auto-scaling
      to add or remove servers based on the traffic.
    - We can use containerization technologies like Docker and orchestration
      tools like Kubernetes to easily manage and scale the application across
      multiple machines.

3. Caching
    - We can use caching to store the frequently accessed data in memory. This
      helps to reduce the load on the database and improve the performance of
      the application. We can use Redis or Memcached to store the cache data.

4. Database Sharding
    - We can use database sharding to distribute the data across multiple
      databases. This helps to reduce the load on a single database and improve
      the performance of the application. We can use a consistent hashing
      algorithm to shard the data.
    - We can use a master-slave architecture to replicate the data across
      multiple databases. This helps to improve the availability of the system.

5. Asynchronous Processing
    - We can use asynchronous processing to handle the requests in parallel.
      This helps to improve the throughput of the system. We can use a message
      queue to process the new subscriber requests asynchronously.
    - Redis or RabbitMQ can be used as a message queue.

6. Rate Limiting and Throttling
    - We can use rate limiting and throttling to limit the number of requests
      per second per user. This helps to prevent the system from being
      overloaded. We can
      use a token bucket algorithm to implement rate limiting and throttling.

### Challenges

1. Load Balancing
    - We need to make sure that the load balancer is not a single point of
      failure. We can use multiple load balancers to avoid this problem.
    - We need to make sure that the load balancer is not a bottleneck. We can
      use a hardware load balancer to avoid this problem.
   
2. Horizontal Scaling
    - We need to make sure that the application is stateless and is
      fault-tolerant. This helps to
      easily scale the application horizontally.
   
3. Latency
    - As the load increases, the response time of the API might also increase.
      It's essential to monitor and optimize the latency of the API to ensure a
      good user experience.
   
4. Consistency
    - We need to make sure that the data is consistent across all the servers.
      We can use a distributed cache to store the data.
   
5. Cost Management
    - We need to make sure that the cost of scaling the application is
      manageable.
    - We can cost monitoring tools provided by cloud providers to stay informed
      about resource consumption.
   
6. Security
    - Increased traffic can make API more susceptible to various security
      threats, such as DDoS attacks. We need to implement robust security
      measures,
      including rate limiting, firewalls, and thorough input validation.
   
7. Testing at Scale
    - Testing the performance of the system at scale is crucial. We need to
      conduct
      realistic load testing to identify potential bottlenecks and weaknesses in
      the architecture.
   
8. Documentation and Communication
    - We need to make sure that the documentation is up-to-date As system
      scales, it becomes essential to have up-to-date documentation and clear
      communication channels within the team. This helps to avoid
      confusion and improve productivity.

