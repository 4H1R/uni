import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function main() {
  // Create a user
  const user = await prisma.$queryRaw`INSERT INTO users (name, email, password) VALUES ('John Doe', 'john.doe@example.com', 'password123')`

  // Create a message
  const message = await prisma.$queryRaw`INSERT INTO messages (title, body, email) VALUES ('Hello', 'Hello, world!', 'john.doe@example.com')`

  console.log('User created:', user)
  console.log('Message created:', message)
}

main()
  .catch(e => {
    throw e
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
