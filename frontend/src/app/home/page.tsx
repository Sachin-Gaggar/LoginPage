"use client";
import { useRouter, useSearchParams } from "next/navigation";
import LogoutButton from "./LogoutButton";

export default function Home() {
  const searchParams = useSearchParams();
  const first_name = searchParams.get("first_name");
  const last_name = searchParams.get("last_name");

  const getGreeting = () => {
    const currentHour = new Date().getHours();
    if (currentHour < 12) {
      return "Good morning";
    } else if (currentHour < 18) {
      return "Good afternoon";
    } else {
      return "Good evening";
    }
  };
  return (
    <main className="flex min-h-screen flex-col p-24">
      <div className="flex justify-end mb-4">
        <LogoutButton />
      </div>
      <div className="text-center">
        <h1 className="text-4xl font-bold mb-4">
          {getGreeting()}. Mr {`${first_name} ${last_name}`}
        </h1>
        <p className="text-lg">This is your home page</p>
      </div>
    </main>
  );
}
